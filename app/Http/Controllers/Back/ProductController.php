<?php

namespace App\Http\Controllers\Back;

use App\Events\ProductStockUpdatedEvent;
use App\Exports\ProductsExport;
use Illuminate\Auth\Access\AuthorizationException;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\Back\Product\{
    StoreProductRequest,
    UpdateProductRequest
};
use App\Models\{Price,
    SizeType,
    Warehouse,
    Product,
    Specification,
    SpecificationGroup,
    SpecType,
    AttributeGroup,
    Brand,
    Category};
use Carbon\Carbon;
use Illuminate\Http\{Request, JsonResponse};
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Resources\Datatable\ProductCollection;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\{Response, BinaryFileResponse};

class ProductController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Product::class, 'product');
    }

    public function index()
    {
        return view('back.products.index');
    }

    public function apiIndex(Request $request)
    {
        $this->authorize('products.index');

        $products = Product::datatableFilter($request);

        $products = datatable($request, $products);

        return new ProductCollection($products);
    }

    public function getProductByTitle()
    {
        $request=request();
        if ($request->has('q') and $request->filled('q')){
            $products=Product::where('title',"LIKE","%"."{$request->q}"."%")->get();
            $items=collect();
            $products->each(function ($product)use ($items){
               $items->push([
                   'id'=>$product->id,
                   'text'=>$product->title,
                   'title'=>$product->title,
                   'image'=>$product->image,
               ]);
            });
            return response()->json([
                'items'=>$items
            ]);
        }
        return response()->json([]);

    }

    public function indexPrices(Request $request)
    {
        $this->authorize('products.prices');

        $products = Product::filter($request)->customPaginate($request);

        return view('back.products.prices', compact('products'));
    }

    public function updatePrices(Request $request)
    {

        $this->authorize('products.prices');

        $request->validate([
            'products' => 'required|array',
        ]);

        $products_id = array_keys($request->products);
        $prices_count = Price::whereIn('product_id', $products_id)->count() * 2;
        $max_input_vars = ini_get('max_input_vars');

        if ($prices_count + 5 > $max_input_vars) {
            throw ValidationException::withMessages([
                'prices' => 'لطفا مقدار max_input_vars را در فایل php.ini تغییر دهید.'
            ]);
        }

        foreach ($request->products as $key => $value) {
            $product = Product::find($key);

            if (!$product) {
                continue;
            }


            foreach ($product->prices as $price) {
                if (!isset($value['prices'][$price->id])) {
                    continue;
                }

                $request_price = $value['prices'][$price->id];

                if (isset($request_price['price']) && isset($request_price['stock']) && ($request_price['price'] != $price->price || $request_price['stock'] != $price->stock)) {

                    $price->createChange(
                        $request_price['price'],
                        $price->discount,
                        $request_price['stock']
                    );

                    $price->update([
                        'price' => $request_price['price'],
                        'stock' => $request_price['stock'],
                        'discount_price' => get_discount_price($request_price['price'], $price->discount),
                    ]);
                }
            }
        }

        // clear product caches
        Product::clearCache();

        return response('success');
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create([
            'title'             => $request->title,
            'title_en'          => $request->title_en,
            'sku'               => $request->sku,
            'category_id'       => $request->category_id,
            'warehouse_id'      => $request->warehouse_id,
            'spec_type_id'      => spec_type($request),
            'size_type_id'      => $request->size_type_id,
            'weight'            => $request->weight,
            'unit'              => $request->unit,
            "cart_max"          => $request->cart_max,
            "cart_min"          => $request->cart_min,
            'price_type'        => "multiple-price",
            'type'              => $request->type,
            'description'       => $request->description,
            'short_description' => $request->short_description,
            'special'           => (bool)$request->special,
            'slug'              => $request->slug ?: $request->title,
            'meta_title'        => $request->meta_title,
            'image_alt'         => $request->image_alt,
            'meta_description'  => $request->meta_description,
            'published'         => $request->published,
            'publish_date'      => $request->publish_date ? Verta::parse($request->publish_date)->datetime() : NULL,
        ]);

        // update product brand
        $this->updateProductBrand($product, $request);

        // update product prices
        $this->updateProductPrices($product, $request);

        // update product files
        $this->updateProductFiles($product, $request);

        // update product specifications
        $this->updateProductSpecifications($product, $request);

        // update product images
        $this->updateProductImage($product, $request);

        // update product attribute pictures
        $this->updateProductAttributePictures($product, $request);

        // update product categories
        $this->updateProductCategories($product, $request);

        // update recommended products
        $this->updateRecommendedProducts($product, $request);

        // update product sizes
        $this->updateProductSizes($product, $request);

        toastr()->success('محصول با موفقیت ایجاد شد.');

        return response("success");
    }

    public function create(Request $request)
    {
        $categories = Category::where('type', 'productcat')->orderBy('ordering')->get();
        $specTypes = SpecType::all();
        $sizeTypes = SizeType::get();
        $attributeGroups = AttributeGroup::orderBy('ordering')->get();
        $warehouses = Warehouse::get();

        $copy_product = $request->product ? Product::where('slug', $request->product)->first() : NULL;

        return view('back.products.create', compact(
            'categories',
            'specTypes',
            'sizeTypes',
            'attributeGroups',
            'copy_product',
            'warehouses'
        ));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('type', 'productcat')->orderBy('ordering')->get();
        $specTypes = SpecType::all();
        $sizeTypes = SizeType::get();
        $attributeGroups = AttributeGroup::orderBy('ordering')->get();
        $warehouses = Warehouse::get();

        return view('back.products.edit', compact(
            'product',
            'categories',
            'specTypes',
            'sizeTypes',
            'attributeGroups',
            'warehouses'
        ));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update([
            'title'             => $request->title,
            'title_en'          => $request->title_en,
            'sku'               => $request->sku,
            'category_id'       => $request->category_id,
            'warehouse_id'      => $request->warehouse_id,
            'spec_type_id'      => spec_type($request),
            'size_type_id'      => $request->size_type_id,
            'weight'            => $request->weight,
            'unit'              => $request->unit,
            "cart_max"          => $request->cart_max,
            "cart_min"          => $request->cart_min,
            'price_type'        => "multiple-price",
            'type'              => $request->type,
            'description'       => $request->description,
            'short_description' => $request->short_description,
            'special'           => (bool)$request->special,
            'slug'              => $request->slug ?: $request->title,
            'meta_title'        => $request->meta_title,
            'image_alt'         => $request->image_alt,
            'meta_description'  => $request->meta_description,
            'published'         => $request->published,
            'publish_date'      => $request->publish_date ? Verta::parse($request->publish_date)->datetime() : NULL,
        ]);

        // update product brand
        $this->updateProductBrand($product, $request);

        // update product prices
        $this->updateProductPrices($product, $request);

        // update product files
        $this->updateProductFiles($product, $request);

        // update product specifications
        $this->updateProductSpecifications($product, $request);

        // update product images
        $this->updateProductImage($product, $request);

        // update product attribute pictures
        $this->updateProductAttributePictures($product, $request);

        // update product categories
        $this->updateProductCategories($product, $request);

        // update recommended products
        $this->updateRecommendedProducts($product, $request);

        // update product sizes
        $this->updateProductSizes($product, $request);

        toastr()->success('محصول با موفقیت ویرایش شد.');

        return response("success");
    }

    public function image_store(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|file|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        $image = $request->file('file');

        $currentDateTime = Carbon::now()->format('Ymd-His');
        $imageName = "img-{$currentDateTime}-" . time() . Str::random(8) . Str::random(8) . ".{$image->getClientOriginalExtension()}";

        $image->storeAs('tmp', $imageName);

        return response()->json(['imagename' => $imageName]);
    }

    public function image_delete(Request $request)
    {
        $filename = $request->get('filename');

        if (Storage::exists('tmp/' . $filename)) {
            Storage::delete('tmp/' . $filename);
        }

        return response('success');
    }

    public function destroy(Product $product)
    {
        $product->tags()->detach();
        $product->specifications()->detach();

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        foreach ($product->gallery as $image) {
            if (Storage::disk('public')->exists($image->image)) {
                Storage::disk('public')->delete($image->image);
            }

            $image->delete();
        }

        $product->delete();

        return response('success');
    }

    public function multipleDestroy(Request $request)
    {
        $this->authorize('products.delete');

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:products,id',
        ]);

        foreach ($request->ids as $id) {
            $product = Product::find($id);
            $this->destroy($product);
        }

        return response('success');
    }

    public function generate_slug(Request $request)
    {
        $request->validate([
            'title' => 'required',
        ]);

        $slug = SlugService::createSlug(Product::class, 'slug', $request->title);

        return response()->json(['slug' => $slug]);
    }

    /**
     * @param Request $request
     * @return BinaryFileResponse
     * @throws AuthorizationException
     */
    public function export(Request $request): BinaryFileResponse
    {
        $this->authorize('products.export');

        $products = Product::datatableFilter($request)->get();

        switch ($request->export_type) {
            case 'excel': {
                return $this->exportExcel($products, $request);
            }
            default: {
                // return $this->exportPrint($products, $request);
            }
        }
    }

    public function ajax_get(Request $request): JsonResponse
    {
        $products = Product::where('title', 'like', '%' . $request->term . '%')
            ->select('id', 'title')
            ->get();

        return response()->json([
            "status" => Response::HTTP_OK,
            "products" => $products
        ], Response::HTTP_OK);
    }

    //------------- Category methods

    public function categories()
    {
        $this->authorize('products.category');

        $categories = Category::where('type', 'productcat')->whereNull('category_id')
            ->with('childrenCategories')
            ->orderBy('ordering')
            ->get();

        return view('back.products.categories', compact('categories'));
    }

    private function updateProductPrices(Product $product, Request $request)
    {
        if ($product->isDownload()) {
            return;
        }

        $prices_id = [];

        foreach ($request->prices as $price) {
            $time = NULL;
            if (isset($price['discount_expire']) && $price['discount_expire']) {
                $time = Carbon::instance(Verta::parse($price['discount_expire'])->datetime())->toDateTimeString() ?? NULL;
            }
            $attributes = array_filter($price['attributes']);

            $update_price = FALSE;

            foreach ($product->prices()->withTrashed()->get() as $product_price) {
                $product_price_attributes = $product_price->get_attributes()->get()->pluck('id')->toArray();

                sort($attributes);
                sort($product_price_attributes);

                if (array_diff($product_price_attributes, $price['attributes']) === array_diff($price['attributes'], $product_price_attributes)
                    and ($product_price->stock == 0 and $price['stock'] > 0)) {
                    event(new ProductStockUpdatedEvent($product, $product_price));
                }

                if ($attributes == $product_price_attributes) {
                    $update_price = $product_price;
                    break 1;
                }
            }

            $price["discount_price"] = is_null($price["discount"]) || is_null($price["discount_price"]) || $price["price"] == $price["discount_price"] ? $price["price"] : $price["discount_price"];

            if ($update_price) {
                $update_price->createChange(
                    $price["price"],
                    $price["discount"],
                    $price["discount_price"]
                );

                $update_price->update([
                    "price"                 => $price["price"],
                    "discount"              => $price["discount"],
                    "discount_price"        => $price["discount_price"],
                    "stock"                 => $price["stock"],
                    "discount_expire_at"    => $price["discount"] ? $time : NULL,
                    "deleted_at"            => NULL,
                ]);

                $update_price->get_attributes()->sync($attributes);

                $prices_id[] = $update_price->id;
            } else {

                $insert_price = $product->prices()->create(
                    [
                        "price"                 => $price["price"],
                        "discount"              => $price["discount"],
                        "discount_price"        => $price["discount_price"],
                        "stock"                 => $price["stock"],
                        "discount_expire_at"    => $price["discount"] ? $time : NULL,
                    ]
                );

                foreach ($attributes as $attribute) {
                    $insert_price->get_attributes()->attach([$attribute]);
                }

                $insert_price->createChange($price["price"], $price["discount"], $price["discount_price"]);

                $insert_price->createChange(
                    $price["price"],
                    $price["discount"],
                    $price["discount_price"],
                    $price["stock"]
                );

                $prices_id[] = $insert_price->id;
            }
        }

        $product->prices()->whereNotIn('id', $prices_id)->delete();
    }

    private function updateProductFiles(Product $product, Request $request)
    {
        if ($product->isPhysical()) {
            return;
        }

        $prices_id = [];
        $ordering = 1;

        foreach ($request->download_files as $price) {

            $update_price = FALSE;

            if (isset($price['price_id'])) {
                $update_price = $product->prices()->withTrashed()->where('prices.id', $price['price_id'])->first();
            }

            if ($update_price) {

                $update_price->createChange(
                    $price["price"],
                    $price["discount"]
                );

                $update_price->update([
                    "price" => $price["price"],
                    "discount" => $price["discount"],
                    "discount_price" => get_discount_price($price["price"], $price["discount"]),
                    "deleted_at" => NULL,
                    "ordering" => $ordering++
                ]);

                $update_price->updateFile($price['title'], $price['file'] ?? NULL, $price['status']);

                $prices_id[] = $update_price->id;
            } else {
                $insert_price = $product->prices()->create(
                    [
                        "price" => $price["price"],
                        "discount" => $price["discount"],
                        "discount_price" => get_discount_price($price["price"], $price["discount"]),
                        "ordering" => $ordering++
                    ]
                );

                $insert_price->createFile($price['title'], $price['file'], $price['status']);

                $insert_price->createChange($price["price"], $price["discount"]);

                $insert_price->createChange(
                    $price["price"],
                    $price["discount"]
                );

                $prices_id[] = $insert_price->id;
            }
        }

        $delete_prices = $product->prices()->whereNotIn('id', $prices_id)->get();

        foreach ($delete_prices as $delete_price) {
            $file = $delete_price->file;

            if ($file) {
                Storage::disk('downloads')->delete('product-files/' . $file->file);
                $file->delete();
            }

            $delete_price->delete();
        }
    }

    private function updateProductSpecifications(Product $product, Request $request)
    {
        $product->specifications()->detach();
        $group_ordering = 0;

        if ($request->specification_group) {
            foreach ($request->specification_group as $group) {

                if (!isset($group['specifications'])) {
                    continue;
                }

                $spec_group = SpecificationGroup::firstOrCreate([
                    'name' => $group['name'],
                ]);

                $specification_ordering = 0;

                foreach ($group['specifications'] as $specification) {
                    $spec = Specification::firstOrCreate([
                        'name' => $specification['name']
                    ]);

                    $product->specifications()->attach([
                        $spec->id => [
                            'specification_group_id' => $spec_group->id,
                            'group_ordering' => $group_ordering,
                            'specification_ordering' => $specification_ordering++,
                            'value' => $specification['value'],
                            'special' => isset($specification['special']) ? TRUE : FALSE
                        ]
                    ]);
                }

                $group_ordering++;
            }
        }
    }

    private function updateProductBrand(Product $product, Request $request)
    {
        if ($request->brand) {
            $brand = Brand::firstOrCreate(
                [
                    'name' => $request->brand,
                ]
            );

            $product->update([
                'brand_id' => $brand->id
            ]);
        }
    }

    private function updateProductImage(Product $product, Request $request)
    {
        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $file = $request->image;
            $name = uniqid() . '_' . $product->id . '.' . $file->getClientOriginalExtension();
            $request->image->storeAs('products', $name);

            $product->image = '/uploads/products/' . $name;
            $product->save();
        }
        if ($request->hasFile('second_image')) {
            if ($product->second_image && Storage::disk('public')->exists($product->second_image)) {
                Storage::disk('public')->delete($product->second_image);
            }

            $file = $request->second_image;
            $name = uniqid() . '_' . $product->id . '.' . $file->getClientOriginalExtension();
            $request->second_image->storeAs('products', $name);

            $product->second_image = '/uploads/products/' . $name;
            $product->save();
        }
    }

    private function updateProductAttributePictures(Product $product, Request $request)
    {
        $attributeDeletedPicturesArray = explode(',', $request->attribute_deleted_pictures);
        $product->attributePicture()->whereIn('picture', $attributeDeletedPicturesArray)->delete();
        Storage::disk('public')->delete($attributeDeletedPicturesArray);

        $attributePictureRows = array();
        if ($request->attribute_pictures != '') {
            foreach ($request->attribute_pictures as $key => $attributePicturesString) {
                $attributePicturesArray = explode(',', $attributePicturesString);

                foreach ($attributePicturesArray as $attributePicture) {
                    $attributePicture = str_replace('/uploads/products/', '', $attributePicture);

                    if (Storage::exists("tmp/{$attributePicture}") && !Storage::exists("products/{$attributePicture}")) {
                        Storage::move("tmp/{$attributePicture}", "products/{$attributePicture}");
                        $attributePictureRows[] = [
                            'product_id' => $product->id,
                            'attribute_id' => $key,
                            'picture' => "/uploads/products/{$attributePicture}"
                        ];
                    }
                }
            }

            $product->attributePicture()->insert($attributePictureRows);
            $product->attributePicture()->whereNotIn('attribute_id', array_keys($request->attribute_pictures))->delete();
        }
    }

    private function updateProductCategories(Product $product, Request $request)
    {
        if ($request->categories) {
            $product->categories()->sync(array_merge($request->categories, [$product->category_id]));
        } else {
            $product->categories()->sync([$product->category_id]);
        }
    }

    private function updateRecommendedProducts(Product $product, Request $request)
    {
        if (!is_null($request->recommended_products)) {
            $request->recommended_products = array_unique($request->recommended_products);

            $product->recommendedProducts()
                ->whereNotIn('recommended_product_id', $request->recommended_products)
                ->delete();

            $recommendedProducts = [];
            foreach ($request->recommended_products as $recommendedProduct) {
                if ($product->recommendedProducts()->where('recommended_product_id', $recommendedProduct)->exists()
                    || $recommendedProduct == $product->id) {
                    continue;
                }

                $recommendedProducts[] = [
                    'product_id' => $product->id,
                    'recommended_product_id' => $recommendedProduct
                ];
            }

            $product->recommendedProducts()->insert($recommendedProducts);
        }
    }

    private function updateProductSizes(Product $product, Request $request)
    {
        $product->sizes()->detach();

        if (!$request->sizes) return;

        $ordering      = 1;
        $groupOrdering = 1;

        foreach ($request->sizes as $group => $sizes) {
            foreach ($sizes as $size_id => $value) {
                $product->sizes()->attach(
                    [
                        $size_id => [
                            'group'    => $groupOrdering,
                            'value'    => $value,
                            'ordering' => $ordering++
                        ]
                    ]
                );
            }

            $groupOrdering++;
        }
    }

    private function exportExcel($products, Request $request): BinaryFileResponse
    {
        return Excel::download(new ProductsExport($products, $request), 'products.xlsx');
    }

    private function exportPrint($products, Request $request)
    {
        //
    }
}
