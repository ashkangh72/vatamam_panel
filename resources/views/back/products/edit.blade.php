@extends('back.layouts.master')

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('back/assets/css/pages/products/all.css') }}?v=2">
@endpush

@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb no-border">
                                    <li class="breadcrumb-item">مدیریت
                                    </li>
                                    <li class="breadcrumb-item">مدیریت محصولات
                                    </li>
                                    <li class="breadcrumb-item active">ویرایش محصول
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="main-card" class="content-body">
                <form class="form" id="product-edit-form" action="{{ route('admin.products.update', ['product' => $product]) }}" data-redirect="{{ route('admin.products.index') }}" method="post">
                    @csrf
                    @method('put')

                    <div class="row match-height">
                        <div class="col-md-12">
                            <div class="card overflow-hidden">
                                <div class="card-header">
                                    <h4 class="card-title">اطلاعات محصول "{{ $product->title }}"</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <div class="row">
                                         <div class="col-12 col-lg-2">
                                             <ul class="nav nav-tabs nav-left flex-lg-column" role="tablist">
                                                 <li class="nav-item">
                                                     <a class="nav-link active" id="baseVerticalLeft-tab1" data-toggle="tab" aria-controls="tabVerticalLeft1" href="#tabVerticalLeft1" role="tab" aria-selected="true">اطلاعات کلی</a>
                                                 </li>
                                                 <li class="nav-item">
                                                     <a class="nav-link" id="productMetaTab" data-toggle="tab" aria-controls="tabProductMeta" href="#tabProductMeta" role="tab" aria-selected="false">سئو</a>
                                                 </li>
                                                 <li class="nav-item physical-item">
                                                     <a class="nav-link" id="product-prices-tab-nav" data-toggle="tab" aria-controls="product-prices-tab" href="#product-prices-tab" role="tab" aria-selected="false">قیمت</a>
                                                 </li>
                                                 <li class="nav-item download-item">
                                                     <a class="nav-link" id="product-files-tab-nav" data-toggle="tab" aria-controls="product-files-tab" href="#product-files-tab" role="tab" aria-selected="false">فایل</a>
                                                 </li>
                                                 <li class="nav-item">
                                                     <a class="nav-link" id="productImageTab" data-toggle="tab" aria-controls="tabProductImage" href="#tabProductImage" role="tab" aria-selected="false">تصاویر</a>
                                                 </li>
                                                 <li class="nav-item">
                                                     <a class="nav-link" id="specification-tab" data-toggle="tab" aria-controls="tabSpecification" href="#tabSpecification" role="tab" aria-selected="false">مشخصات</a>
                                                 </li>
                                                 <li class="nav-item">
                                                     <a class="nav-link" id="recommendedProductsTab" data-toggle="tab" aria-controls="tabRecommendedProducts" href="#tabRecommendedProducts" role="tab" aria-selected="false">محصولات پیشنهادی</a>
                                                 </li>
                                                 <li class="nav-item">
                                                     <a class="nav-link" id="sizes-tab" data-toggle="tab" aria-controls="tabSize" href="#tabSize" role="tab" aria-selected="false">سایز بندی</a>
                                                 </li>
                                             </ul>
                                         </div>
                                           <div class="col-12 col-lg-10">
                                               <div class="tab-content">
                                                   <div class="tab-pane active" id="tabVerticalLeft1" role="tabpanel" aria-labelledby="baseVerticalLeft-tab1">
                                                       <div class="col-md-12">
                                                           <div class="form-body">

                                                               <div class="row">
                                                                   <div class="col-md-6">
                                                                       <div class="form-group">
                                                                           <label>عنوان</label>
                                                                           <input type="text" class="form-control" name="title" value="{{ $product->title }}">
                                                                       </div>
                                                                   </div>
                                                                   <div class="col-md-6">
                                                                       <div class="form-group">
                                                                           <label>عنوان انگلیسی</label>
                                                                           <input type="text" class="form-control" name="title_en" value="{{ $product->title_en }}">
                                                                       </div>
                                                                   </div>
                                                                   <div class="col-md-4">
                                                                       <div class="form-group">
                                                                           <label>دسته بندی اصلی</label>
                                                                           <select class="form-control product-category" name="category_id">
                                                                               @foreach ($categories as $category)
                                                                                   <option
                                                                                       class="l{{ $category->parents()->count() + 1 }} {{ $category->categories()->count() ? 'non-leaf' : '' }}"
                                                                                       data-pup="{{ $category->category_id }}"
                                                                                       {{ ($product->category_id == $category->id) ? 'selected' : '' }}
                                                                                       value="{{ $category->id }}">{{ $category->title }}
                                                                                   </option>
                                                                               @endforeach
                                                                           </select>
                                                                       </div>
                                                                   </div>

                                                                   <div class="col-md-4 col-12 physical-item">
                                                                       <div class="form-group">
                                                                           <label>انبار</label>
                                                                           <select name="warehouse_id" id="product-warehouse-id" class="form-control" {{ $product->isPhysical() ? 'required' : '' }}>
                                                                               <option value="">انتخاب کنید</option>
                                                                               @foreach ($warehouses as $warehouse)
                                                                                   <option value="{{ $warehouse->id }}" {{ $product->warehouse_id == $warehouse->id ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                                                               @endforeach
                                                                           </select>
                                                                       </div>
                                                                   </div>

                                                                   <div class="col-md-4 col-12">
                                                                       <div class="form-group">
                                                                           <label>نوع محصول</label>
                                                                           <select name="type" id="product-type" class="form-control">
                                                                               <option value="physical" {{ $product->isPhysical() ? 'selected' : '' }}>محصول فیزیکی</option>
                                                                               <option value="download" {{ $product->isDownload() ? 'selected' : '' }}>محصول دانلودی</option>
                                                                           </select>
                                                                       </div>
                                                                   </div>

                                                                   <div class="col-md-4 col-12">
                                                                       <div class="form-group">
                                                                           <label>برند</label>
                                                                           <input id="brand" type="text" class="form-control" name="brand" value="{{ $product->brand ? $product->brand->name : '' }}">
                                                                       </div>
                                                                   </div>
                                                                   <div class="col-md-4 col-12 physical-item">
                                                                       <div class="form-group">
                                                                           <label>وزن</label>
                                                                           <input type="number" class="form-control" name="weight" value="{{ $product->weight }}">
                                                                       </div>
                                                                   </div>
                                                                   <div class="col-md-4 col-12 physical-item">
                                                                       <div class="form-group">
                                                                           <label>واحد</label>
                                                                           <input type="text" class="form-control" name="unit" value="{{ $product->unit }}">
                                                                       </div>
                                                                   </div>

                                                                   <div class="col-md-4 col-12">
                                                                       <div class="form-group">
                                                                           <label>بیشترین تعداد مجاز در هر سفارش</label>
                                                                           <input type="number" class="form-control" name="cart_max" value="{{ $product->cart_max ?? NULL }}" min="1">
                                                                       </div>
                                                                   </div>
                                                                   <div class="col-md-4 col-12">
                                                                       <div class="form-group">
                                                                           <label>کمترین تعداد مجاز در هر سفارش</label>
                                                                           <input type="number" class="form-control" name="cart_min" value="{{ $product->cart_min ?? NULL }}" min="1">
                                                                       </div>
                                                                   </div>

                                                                   <div class="col-md-4 col-12">
                                                                       <div class="form-group">
                                                                           <label>شناسه(sku)</label>
                                                                           <input type="text" class="form-control" name="sku" value="{{ $product->sku ?? NULL }}">
                                                                       </div>
                                                                   </div>

                                                                   <div class="col-md-12">
                                                                       <div class="form-group">
                                                                           <label>دسته بندی ها</label>
                                                                           <select class="form-control product-categories" name="categories[]" multiple>
                                                                               @foreach ($categories as $category)
                                                                                   <option
                                                                                       class="l{{ $category->parents()->count() + 1 }} {{ $category->categories()->count() ? 'non-leaf' : '' }}"
                                                                                       data-pup="{{ $category->category_id }}"
                                                                                       {{ ($product->categories()->find($category->id)) ? 'selected' : '' }}
                                                                                       value="{{ $category->id }}">{{ $category->title }}
                                                                                   </option>
                                                                               @endforeach
                                                                           </select>
                                                                       </div>
                                                                   </div>

                                                                   <div class="col-md-12">
                                                                       <div class="form-group">
                                                                           <label>توضیحات کوتاه</label>
                                                                           <textarea class="form-control" name="short_description" rows="3">{{ $product->short_description }}</textarea>
                                                                       </div>
                                                                   </div>
                                                                   <div class="col-md-12">
                                                                       <div class="form-group">
                                                                           <label for="first-name-vertical">توضیحات</label>
                                                                           <textarea id="description" class="form-control" rows="3" name="description">{{ $product->description }}</textarea>
                                                                       </div>
                                                                   </div>
                                                               </div>

                                                           </div>
                                                       </div>
                                                   </div>

                                                   <div class="tab-pane" id="product-prices-tab" role="tabpanel" aria-labelledby="product-prices-tab-nav">
                                                       <div class="row">
                                                           <div class="form-group col-md-3 col-12 offset-md-1">
                                                               <label>{{ $attributeGroups->where('id', 1)->first()->name }}</label>
                                                               <select class="form-control price-attribute-select" id="priceColorId">
                                                                   <option value="">انتخاب کنید</option>
                                                                   @foreach ($attributeGroups->where('id', 1)->first()->get_attributes as $attribute)
                                                                       <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                                                   @endforeach
                                                               </select>
                                                           </div>
                                                           <div class="form-group col-md-3 col-12">
                                                               <label>{{ $attributeGroups->where('id', 2)->first()->name }}</label>
                                                               <select class="form-control price-attribute-select" id="priceSizeId" multiple>
                                                                   <option disabled>انتخاب کنید</option>
                                                                   @foreach ($attributeGroups->where('id', 2)->first()->get_attributes as $attribute)
                                                                       <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                                                   @endforeach
                                                               </select>
                                                               <small id="recommendedProductsHelpBlock" class="form-text text-muted">برای انتخاب چندتایی کلید Ctrl را نگه دارید.</small>
                                                           </div>

                                                           <div class="col-md-3 col-12 text-center mt-2">
                                                               <button id="addProductPrice" type="button" class="btn btn-outline-primary waves-effect waves-light"><i class="feather icon-plus"></i>افزودن قیمت</button>
                                                           </div>
                                                       </div>

                                                       <div id="productPrices">
                                                           @if ($product && $product->isPhysical() && $product->prices->count() > 0)
                                                               @php
                                                                   $colorAttributes = collect();
                                                                   foreach($product->prices as $price) {
                                                                       $colorAttributes = $colorAttributes->merge($price->get_attributes->where('attribute_group_id', 1)->pluck('id', 'name'));
                                                                   }
                                                                   $colorAttributes = $colorAttributes->unique();
                                                                   $iteration = 1;
                                                               @endphp
                                                               @if(count($colorAttributes) > 0)
                                                                   @foreach($colorAttributes as $colorAttributeName => $colorAttributeId)
                                                                       <div class="row single-color" id="singleColor-{{ $colorAttributeId }}">
                                                                           <div class="container" >
                                                                               <label class="badge badge-light col-md-6 offset-md-3" id="priceRowLabel">{{ 'قیمت های رنگ '.$colorAttributeName }}</label>
                                                                               <button type="button" class="btn btn-outline-danger waves-effect waves-light custom-padding remove-product-priceColor">حذف</button>
                                                                           </div>
                                                                           @foreach ($product->prices()->whereHas('get_attributes', function ($attributes) use ($colorAttributeId) {
                                                                                   $attributes->where('attributes.id', $colorAttributeId);
                                                                               })->get() as $price)
                                                                               @include('back.products.partials.prices-include', ['price' => $price, 'colorAttributeId' => $colorAttributeId, 'sizeAttributeId' => $price->get_attributes->where('attribute_group_id', 2)->first()->id])
                                                                               @php $iteration++ @endphp
                                                                           @endforeach
                                                                       </div>
                                                                       <div class="col-md-12"><hr></div>
                                                                   @endforeach
                                                               @endif
                                                           @endif
                                                       </div>
                                                   </div>

                                                   <div class="tab-pane" id="product-files-tab" role="tabpanel" aria-labelledby="product-files-tab-nav">

                                                       <div class="col-md-12">
                                                           <div id="product-files-area">
                                                               @if ($product->isDownload())
                                                                   @foreach ($product->prices()->orderBy('ordering')->get() as $price)
                                                                       @include('back.products.partials.files-include', ['price' => $price])
                                                                   @endforeach
                                                               @endif
                                                           </div>
                                                           <div class="row">
                                                               <div class="col-12 text-center mt-2">
                                                                   <button id="add-product-file" type="button" class="btn btn-outline-primary waves-effect waves-light"><i class="feather icon-plus"></i> افزودن فایل</button>
                                                               </div>
                                                           </div>
                                                       </div>

                                                   </div>

                                                   <div class="tab-pane" id="tabSpecification" role="tabpanel" aria-labelledby="specification-tab">
                                                       <div id="specifications-card" class="card">
                                                           <div class="card-header d-flex justify-content-between align-items-end">
                                                               <h4 class="card-title">مشخصات محصول</h4>
                                                               <p class="font-medium-5 mb-0"><i class="feather icon-help-circle text-muted cursor-pointer"></i></p>
                                                           </div>
                                                           <div class="card-content">
                                                               <div class="card-body ">
                                                                   <div class="row">
                                                                       <div class="col-md-7 text-justify">
                                                                           <p>در این بخش میتوانید مشخصات محصول را وارد کنید. دقت  کنید که محصولات بر اساس نوع مشخصات با یکدیگر مقایسه میشوند. به عنوان مثال یک محصول با نوع مشخصات "گوشی موبایل"  فقط با محصولات این نوع مقایسه میشود</p>
                                                                       </div>
                                                                       <div class="col-md-4">
                                                                           <div class="form-group">
                                                                               <label>نوع مشخصات</label>
                                                                               <input id="specifications_type" class="form-control" name="spec_type" placeholder="مثلا گوشی موبایل" value="{{ $product->specType ? $product->specType->name : '' }}">
                                                                           </div>
                                                                       </div>
                                                                   </div>
                                                                   <div class="form-body pt-2">
                                                                       <div id="specifications-area">
                                                                           @foreach ($product->specificationGroups->unique() as $group)
                                                                               <div class="row mt-2 specification-group">
                                                                                   <div class="col-12">
                                                                                       <div class="row group-row">
                                                                                           <div class="col-md-1">
                                                                                               <span>نام گروه</span>
                                                                                           </div>
                                                                                           <div class="col-md-10 form-group">
                                                                                               <input type="text" class="form-control group-input" data-group_name="{{ $loop->index }}" name="specification_group[{{ $loop->index }}][name]" placeholder="مثال: مشخصات کلی" value="{{ $group->name }}" required>
                                                                                           </div>
                                                                                       </div>
                                                                                   </div>

                                                                                   <div class="all-specifications col-12">
                                                                                       @foreach($product->specifications()->where('specification_group_id', $group->id)->get() as $specification)
                                                                                           <div class="single-specificition">
                                                                                               <div class="row">
                                                                                                   <div class="col-md-1">
                                                                                                       <fieldset>
                                                                                                           <label>
                                                                                                               <input name="specification_group[{{ $loop->parent->index }}][specifications][{{ $loop->index }}][special]" type="checkbox" {{ $specification->pivot->special ? 'checked' : '' }}>
                                                                                                           </label>
                                                                                                       </fieldset>
                                                                                                   </div>
                                                                                                   <div class="col-md-4 form-group">
                                                                                                       <p class="spec-label">عنوان</p>
                                                                                                       <input type="text" class="form-control spec-label" name="specification_group[{{ $loop->parent->index }}][specifications][{{ $loop->index }}][name]" placeholder="مثال: حافظه داخلی" value="{{ $specification->name }}" required>
                                                                                                   </div>

                                                                                                   <div class="col-md-6 form-group">
                                                                                                       <p class="spec-label">مقدار</p>
                                                                                                       <textarea  class="form-control spec-label" rows="1" name="specification_group[{{ $loop->parent->index }}][specifications][{{ $loop->index }}][value]" placeholder="مثال: 32 گیگابایت" required>{{ $specification->pivot->value }}</textarea>
                                                                                                   </div>
                                                                                                   <div class="col-md-1">
                                                                                                       <button type="button" class="btn btn-flat-danger waves-effect waves-light remove-specification custom-padding"><i class="feather icon-minus"></i></button>
                                                                                                   </div>
                                                                                               </div>
                                                                                           </div>
                                                                                       @endforeach
                                                                                   </div>

                                                                                   <div class="col-md-12 text-center">
                                                                                       <button type="button" class="btn btn-flat-success waves-effect waves-light add-specifaction">افزودن مشخصات</button>
                                                                                       <button type="button" class="btn btn-flat-danger waves-effect waves-light remove-group">حذف گروه</button>

                                                                                   </div>
                                                                               </div>
                                                                           @endforeach
                                                                       </div>

                                                                       <div class="row">
                                                                           <div class="col-12 text-center mt-4">
                                                                               <button id="add-product-specification-group" type="button" class="btn btn-outline-primary waves-effect waves-light"><i class="feather icon-plus"></i> افزودن گروه مشخصات</button>
                                                                           </div>
                                                                       </div>

                                                                   </div>
                                                               </div>
                                                           </div>

                                                       </div>
                                                   </div>

                                                   <div class="tab-pane" id="tabProductMeta" role="tabpanel" aria-labelledby="productMetaTab">
                                                       <div class="col-md-12">
                                                           <div class="form-body">

                                                               <div class="row">
                                                                   <div class="col-md-6">
                                                                       <div class="form-group">
                                                                           <label>عنوان سئو</label>
                                                                           <input type="text" class="form-control" name="meta_title" value="{{ $product->meta_title }}">
                                                                       </div>
                                                                   </div>
                                                                   <div class="col-md-6">
                                                                       <div class="form-group">
                                                                           <label>url</label>
                                                                           <input id="slug" type="text" class="form-control" name="slug" value="{{ $product->slug }}">
                                                                           <p>
                                                                               <small >
                                                                                   <a id="generate-product-slug" href="#">ایجاد خودکار</a>
                                                                                   <span id="slug-spinner" class="spinner-grow spinner-grow-sm text-success" role="status" style="display: none;">
                                                                                    <span class="sr-only">Loading...</span>
                                                                                </span>
                                                                               </small>
                                                                           </p>
                                                                       </div>
                                                                   </div>

                                                                   <div class="col-md-6">
                                                                       <div class="form-group">
                                                                           <label>توضیحات سئو</label>
                                                                           <textarea class="form-control" name="meta_description" rows="3">{{ $product->meta_description }}</textarea>
                                                                       </div>
                                                                   </div>
                                                                   <div class="col-md-6">
                                                                       <fieldset class="form-group">
                                                                           <label>کلمات کلیدی</label>
                                                                           <input type="text" name="tags" class="form-control tags" value="{{ $product->getTags }}">
                                                                       </fieldset>
                                                                   </div>

                                                               </div>

                                                           </div>
                                                       </div>
                                                   </div>

                                                   <div class="tab-pane overflow-hidden" id="tabProductImage" role="tabpanel" aria-labelledby="productImageTab">
                                                       <div class="row">
                                                           <div class="col-md-6">
                                                               <div class="row">
                                                                   <fieldset class="form-group col-md-6">
                                                                       <label>تصویر شاخص</label>
                                                                       <div class="custom-file">
                                                                           <input id="image" type="file" accept="image/*" name="image" class="custom-file-input">
                                                                           <label class="custom-file-label" for="image">{{ $product->image }}</label>
                                                                           <p><small>بهترین اندازه <span class="text-danger">{{ config('front.imageSizes.productImage') }}</span> پیکسل میباشد.</small></p>
                                                                       </div>
                                                                   </fieldset>
                                                                   <div class="col-md-6">
                                                                       <img class="img-thumbnail w-50" src="{{ $product->image }}">
                                                                   </div>
                                                               </div>
                                                           </div>
                                                           <div class="col-md-6">
                                                               <div class="row">
                                                                   <fieldset class="form-group col-md-6">
                                                                       <label>تصویر دوم</label>
                                                                       <div class="custom-file">
                                                                           <input id="secondImage" type="file" accept="image/*" name="second_image" class="custom-file-input">
                                                                           <label class="custom-file-label" for="secondImage">{{ $product->second_image }}</label>
                                                                           <p><small>بهترین اندازه <span class="text-danger">{{ config('front.imageSizes.productImage') }}</span> پیکسل میباشد.</small></p>
                                                                       </div>
                                                                   </fieldset>
                                                                   <div class="col-md-6">
                                                                       <img class="img-thumbnail w-50" src="{{ $product->second_image }}">
                                                                   </div>
                                                               </div>
                                                           </div>
                                                           <div class="col-md-6">
                                                               <div class="form-group">
                                                                   <label>متن جایگزین تصویر شاخص</label>
                                                                   <input type="text" class="form-control" name="image_alt" value="{{ $product->image_alt }}">
                                                               </div>
                                                           </div>
                                                       </div>

                                                       <div class="row">
                                                           <div class="col-md-4 col-12">
                                                               <div class="form-group">
                                                                   <label>{{ $attributeGroups->where('id', 1)->first()->name }}</label>
                                                                   <select class="form-control price-attribute-select" id="attributeId">
                                                                       <option value="">انتخاب کنید</option>
                                                                       @foreach ($attributeGroups->where('id', 1)->first()->get_attributes as $attribute)
                                                                           <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                                                       @endforeach
                                                                   </select>
                                                               </div>
                                                           </div>

                                                           <div class="col-md-3 col-12 text-center mt-2">
                                                               <button id="addProductAttributePicture" type="button" class="btn btn-outline-primary waves-effect waves-light"><i class="feather icon-plus"></i>افزودن رنگبندی تصویری</button>
                                                           </div>
                                                       </div>

                                                       <div class="row" id="attributePictureRows">
                                                           <input type="hidden" id="attributeDeletedPicturesInput" name="attribute_deleted_pictures">
                                                           @foreach($product->getAttributePictureUniqueAttribute()->get() as $attributePicture)
                                                               <div class="col-md-12 single-attribute-picture-row" >
                                                                   <div id="attributePictureRow-{{ $attributePicture->attribute_id }}">
                                                                       <label>تصاویر رنگ {{ $attributePicture->attribute->name }} ( <small>بهترین اندازه <span class="text-danger">{{ config('front.imageSizes.productImage') }}</span> پیکسل میباشد.</small> ). </label>

                                                                       <div class="dropzone dropzone-area mb-2 attribute-pictures-dropzone" id="attributePicturesDropzone-{{ $attributePicture->attribute_id }}">
                                                                           <input type="hidden" id="attributePicturesDropzoneInput-{{ $attributePicture->attribute_id }}" name="attribute_pictures[{{ $attributePicture->attribute_id }}]">
                                                                           <div class="dz-message">تصاویر {{ $attributePicture->attribute->name }} را به اینجا بکشید</div>
                                                                       </div>
                                                                   </div>
                                                                   <div class="col-md-12">
                                                                       <button type="button" class="btn btn-flat-danger waves-effect waves-light remove-product-attribute-picture custom-padding btn-block">حذف</button>
                                                                   </div>
                                                                   <div class="col-md-12"><hr></div>
                                                               </div>
                                                           @endforeach
                                                       </div>
                                                   </div>

                                                   <div class="tab-pane" id="tabRecommendedProducts" role="tabpanel" aria-labelledby="recommendedProductsTab">
                                                       <div class="col-md-12">
                                                           <div class="form-body">
                                                               <div class="form-group row">
                                                                   <div class="col-sm-3">
                                                                       <input type="text" name="recommended_products_search" id="recommendedProductsSearch" class="form-control" autocomplete="off" placeholder="نام محصول را جستجو کنید ...">
                                                                   </div>
                                                                   <div class="col-sm-9">
                                                                       <select name="recommended_products[]" id="recommendedProducts" class="form-control" multiple>
                                                                           @if($product->recommendedProducts->count() > 0)
                                                                               @foreach($product->recommendedProducts as $recommendedProduct)
                                                                                   <option value="{{ $recommendedProduct->recommended_product_id }}" selected>
                                                                                       {{ $recommendedProduct->recommended_product_id . ' - ' . $recommendedProduct->recommendProduct->title }}
                                                                                   </option>
                                                                               @endforeach
                                                                           @endif
                                                                       </select>
                                                                       <small id="recommendedProductsHelpBlock" class="form-text text-muted">برای انتخاب چندتایی کلید Ctrl را نگه دارید.</small>
                                                                   </div>
                                                               </div>
                                                           </div>
                                                       </div>
                                                   </div>

                                                   @include('back.products.partials.sizes-tab')
                                               </div>
                                           </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="form-body">

                                    <div class="row">
                                        <div class="col-md-3 col-12">
                                            <div class="form-group">
                                                <label>تاریخ انتشار</label>
                                                <input autocomplete="off" type="text" class="form-control" id="publish_date_picker" value="{{ $product->publish_date ? tverta($product->publish_date)->timestamp : '' }}">
                                                <input type="hidden" name="publish_date" id="publish_date" value="{{ $product->publish_date ? tverta($product->publish_date) : '' }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">

                                        <div class="col-md-3">
                                            <fieldset class="checkbox">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input type="checkbox" name="special" {{ $product->special ? 'checked' : '' }}>
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span>محصول ویژه؟</span>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-3">
                                            <ul class="list-unstyled mb-0">
                                                <li class="d-inline-block mr-2">
                                                    <fieldset>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input" name="published" id="customRadio1" value="1" {{ $product->published ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="customRadio1">انتشار</label>
                                                        </div>
                                                    </fieldset>
                                                </li>
                                                <li class="d-inline-block mr-2">
                                                    <fieldset>
                                                        <div class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input" name="published" id="customRadio2" value="0" {{ !$product->published ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="customRadio2">پیش نویس</label>
                                                        </div>
                                                    </fieldset>
                                                </li>

                                            </ul>
                                        </div>

                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12 text-center">
                                            <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">ویرایش محصول</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </form>
                <div id="form-progress" class="progress progress-bar-success progress-xl" style="display: none;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width:0%">0%</div>
                </div>
            </div>
        </div>
    </div>

    @include('back.products.partials.specification-template')
    @include('back.products.partials.prices-template')
    @include('back.products.partials.files-template')
@endsection

@include('back.partials.plugins', ['plugins' => ['ckeditor', 'jquery-tagsinput', 'jquery.validate', 'jquery-ui', 'jquery-ui-sortable', 'dropzone', 'persian-datepicker']])

@push('scripts')
    <script>
        /* load saved image gallery */
        let mockImages = [];
        @foreach($product->attributePicture()->get() as $attributePicture)
            if (!mockImages[{{ $attributePicture->attribute_id }}]) {
                mockImages[{{ $attributePicture->attribute_id }}] = []
            }
            mockImages[{{ $attributePicture->attribute_id }}].push({
                name: '{{ $attributePicture->picture }}',
                galleryImage: true,
                type: 'image/jpeg',
                status: 'success',
                upload: {
                    filename: '{{ $attributePicture->picture }}',
                },
                prevFile: true,
                accepted: true,
                image: '{{ $attributePicture->picture }}',
            });
        @endforeach

        let product = {{ $product->id }};

        let groupCount = {{ $product->specificationGroups->unique()->count() }};
        let specificationCount = {{ $product->specifications->unique()->count() }};

        let availableTypes = [
            @foreach($specTypes as $spec_type)
                '{{ $spec_type->name }}',
            @endforeach
        ];

        let specifications_type_first_change = true;

        let priceCount = {{ $product->prices()->count() }};
        let filesCount = {{ $product->files()->count() }};
    </script>
    <script src="{{ asset('back/assets/js/pages/products/all.js') }}?v=6.2"></script>
    <script src="{{ asset('back/assets/js/pages/products/edit.js') }}?v=4"></script>
@endpush
