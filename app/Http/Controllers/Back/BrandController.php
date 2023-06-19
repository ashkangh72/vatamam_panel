<?php

namespace App\Http\Controllers\Back;

use App\Models\Brand;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:products.brands');
    }

    public function index()
    {
        $brands = Brand::latest()->paginate(10);

        return view('back.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('back.brands.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name'  => 'required',
            'image' => 'mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        $brand = Brand::create([
            'name'       => $request->name,
        ]);

        if ($request->hasFile('image')) {
            $file = $request->image;
            $name = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $request->image->storeAs('brands', $name);

            $brand->image = '/uploads/brands/' . $name;
            $brand->save();
        }

        toastr()->success('برند با موفقیت ایجاد شد.');

        return response("success");
    }

    public function edit(Brand $brand)
    {
        return view('back.brands.edit', compact('brand'));
    }

    public function update(Brand $brand, Request $request)
    {
        $this->validate($request, [
            'image' => 'mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        $brand->update([
            'name'       => $request->name,
        ]);

        if ($request->hasFile('image')) {

            if ($brand->image) {
                Storage::disk('public')->delete($brand->image);
            }

            $file = $request->image;
            $name = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $request->image->storeAs('brands', $name);

            $brand->image = '/uploads/brands/' . $name;
            $brand->save();
        }

        toastr()->success('برند با موفقیت ویرایش شد.');

        return response("success");
    }

    public function destroy(Brand $brand)
    {
        if ($brand->image) {
            Storage::disk('public')->delete($brand->image);
        }

        $brand->delete();

        return response('success');
    }

    public function ajax_get(Request $request)
    {
        if ($request->term) {
            $brands = Brand::where('name', 'like', '%' . $request->term . '%')->pluck('name')->toArray();

            return $brands;
        }
    }
}
