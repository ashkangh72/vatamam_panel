@extends('back.layouts.master')

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
                                    <li class="breadcrumb-item">مدیریت</li>
                                    <li class="breadcrumb-item">مدیریت محصولات</li>
                                    <li class="breadcrumb-item active">راهنمای سایز</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="main-card" class="content-body">
                <form id="size-type-values-form" action="{{ route('admin.size-types.updateValues', ['sizeType' => $sizeType]) }}" data-redirect="{{ route('admin.size-types.index') }}" class="form"  method="post">
                    @csrf
                    @method('put')

                    <div class="row">
                        <div class="col-md-12">
                            <div id="size-types-card" class="card">
                                <div class="card-content">
                                    <div class="card-body ">
                                        <div class="form-body">
                                            <div id="values-area" class="all-values">
                                                @if ($sizeType->values->count())
                                                    @foreach ($sizeType->values->groupBy('pivot.group') as $sizes)
                                                        <div class="row mt-2 align-items-center single-value">
                                                            <div class="col-md-1">
                                                                <button type="button" class="btn btn-flat-danger waves-effect waves-light remove-value custom-padding"><i class="feather icon-minus"></i></button>
                                                            </div>

                                                            <div class="col-md-10">
                                                                <div class="row">
                                                                    @foreach ($sizeType->sizes as $size)
                                                                        @php
                                                                            $group = $sizes->first()->pivot->group;
                                                                            $value = $sizeType->values()->where('size_id', $size->id)->where('group', $group)->first()->pivot->value ?? '';
                                                                        @endphp

                                                                        <div class="col-md-3 form-group">
                                                                            <label for="">{{ $size->title }}</label>
                                                                            <input data-size-id="{{ $size->id }}" type="text" class="form-control" name="values[{{ $loop->parent->index }}][{{ $size->id }}]" value="{{ $value }}">
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>

                                                            <div class="col-12">
                                                                <hr class="m-0">
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="row mt-2 align-items-center single-value">
                                                        <div class="col-md-1">
                                                            <button type="button" class="btn btn-flat-danger waves-effect waves-light remove-value custom-padding"><i class="feather icon-minus"></i></button>
                                                        </div>

                                                        <div class="col-md-10">
                                                            <div class="row">
                                                                @foreach ($sizeType->sizes as $size)
                                                                    <div class="col-md-3 form-group">
                                                                        <label for="">{{ $size->title }}</label>
                                                                        <input data-size-id="{{ $size->id }}" type="text" class="form-control" name="values[0][{{ $size->id }}]">
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>

                                                        <div class="col-12">
                                                            <hr class="m-0">
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12 text-center mt-2">
                                                    <button type="button" class="btn btn-flat-success waves-effect waves-light add-value">افزودن مقدار جدید</button>
                                                </div>
                                                <div class="col-12 text-center mt-4">
                                                    <button type="submit" class="btn btn-outline-success waves-effect waves-light">ذخیره تغییرات</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@include('back.partials.plugins', ['plugins' => ['jquery-ui', 'jquery.validate', 'jquery-ui-sortable']])

@push('scripts')
    <script>
        let valuesCount = {{ $sizeType->values->count() }};
    </script>

    <script src="{{ asset('public/back/assets/js/pages/size-types/values.js') }}?v=1"></script>
@endpush
