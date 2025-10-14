@extends('back.layouts.master')

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('public/back/app-assets/plugins/jquery-ui/jquery-ui.css') }}">
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
                                    <li class="breadcrumb-item">مدیریت اسلایدرها
                                    </li>
                                    <li class="breadcrumb-item active">ایجاد اسلاید
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Description -->
                <section class="card">
                    <div class="card-header">
                        <h4 class="card-title">ایجاد اسلاید جدید</h4>
                    </div>
                    <div id="main-card" class="card-content">
                        <div class="card-body">
                            <div class="col-12 col-md-10 offset-md-1">
                                <form class="form" id="slide-create-form" action="{{ route('admin.slides.store') }}">
                                    @csrf
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>عنوان <small>(اختیاری)</small></label>
                                                    <input type="text" class="form-control" name="title">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>لینک <small>(اختیاری)</small></label>
                                                    <input type="text" class="form-control slide-link ltr" name="link">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label>گروه</label>
                                                    <select class="form-control" name="group">
                                                        @if (config('general.sliderGroups'))
                                                            @foreach (config('general.sliderGroups') as $sliderGroup)
                                                                <option value="{{ $sliderGroup['group'] }}">{{ $sliderGroup['name'] }} {{ $sliderGroup['size'] }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <fieldset class="form-group">
                                                    <label>تصویر</label>
                                                    <div class="custom-file">
                                                        <input id="image" type="file" accept="image/*" name="image"
                                                               class="custom-file-input">
                                                        <label class="custom-file-label" for="image"></label>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <fieldset class="form-group">
                                                    <label>نوع اتصال</label>
                                                    <div class="custom-file">
                                                        <select name="linkable_type" onchange="generateConnectHtml(event)"
                                                                id="connect" class="form-control">
                                                            <option value>انتخاب کنید</option>
                                                            <option value="auction" id="auction" data-action="{{route('admin.auctions.search.title')}}">
                                                                مزایده
                                                            </option>
                                                            <option value="category" id="category" data-action="{{route('admin.categories.search.title')}}">
                                                                دسته
                                                            </option>
                                                        </select>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-12 col-md-6" id="connect-wrapper">
                                                <fieldset class="form-group">
                                                    <label>انتخاب مزایده</label>
                                                    <select name="linkable_id" id="linkable_id" class="form-control"></select>
                                                </fieldset>
                                            </div>
                                        </div>
                                        <div class="row justify-content-end">
                                            <div class="col-12 col-md-6">
                                                <fieldset class="checkbox">
                                                    <div class="vs-checkbox-con vs-checkbox-primary">
                                                        <input type="checkbox" name="published" checked>
                                                        <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                        <span>انتشار اسلایدر؟</span>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <button type="submit"
                                                        class="btn btn-primary mr-1 mb-1 waves-effect waves-light">ایجاد
                                                    اسلایدر
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
                <!--/ Description -->
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('public/back/app-assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script
        src="{{ asset('public/back/app-assets/plugins/jquery-validation/localization/messages_fa.min.js') }}"></script>
    <script src="{{ asset('public/back/app-assets/plugins/jquery-ui/jquery-ui.js') }}"></script>

    <script>
        let pages = [
            @foreach($pages as $page)
                "/{{ $page }}",
            @endforeach
        ];
    </script>
    <script src="{{ asset('public/back/assets/js/pages/slides/create.js') }}"></script>
@endpush
