@extends('back.layouts.master')

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('back/app-assets/plugins/jquery-ui/jquery-ui.css') }}">
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
                                    <li class="breadcrumb-item">مدیریت</li>
                                    <li class="breadcrumb-item">مدیریت پوسترها</li>
                                    <li class="breadcrumb-item active">ایجاد پوستر</li>
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
                        <h4 class="card-title">ایجاد پوستر جدید</h4>
                    </div>

                    <div id="main-card" class="card-content">
                        <div class="card-body">
                            <div class="col-12 col-md-10 offset-md-1">
                                <form class="form" id="poster-create-form" action="{{ route('admin.posters.store') }}">
                                    @csrf
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>عنوان <small>(اختیاری)</small></label>
                                                    <input type="text" class="form-control "
                                                           name="title">
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>لینک <small>(اختیاری)</small></label>
                                                    <input type="text" class="form-control poster-link ltr" name="link">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <fieldset class="form-group">
                                                    <label>تصویر</label>
                                                    <div class="custom-file">
                                                        <input id="image" type="file" accept="image/*" name="image"
                                                               class="custom-file-input">
                                                        <label class="custom-file-label" for="image"></label>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label>گروه</label>
                                                    <select class="form-control" name="group">
                                                        @if (config('general.posterGroups'))
                                                            @foreach (config('general.posterGroups') as $posterGroup)
                                                                <option
                                                                    value="{{ $posterGroup['group'] }}">{{ $posterGroup['name'] }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
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

                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <fieldset class="checkbox">
                                                    <div class="vs-checkbox-con vs-checkbox-primary">
                                                        <input type="checkbox" name="published" checked>
                                                        <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                        <span>انتشار پوستر؟</span>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 text-right">
                                                <button type="submit"
                                                        class="btn btn-primary mr-1 mb-1 waves-effect waves-light">ایجاد
                                                    پوستر
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
    <script src="{{ asset('back/app-assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('back/app-assets/plugins/jquery-validation/localization/messages_fa.min.js') }}"></script>
    <script src="{{ asset('back/app-assets/plugins/jquery-ui/jquery-ui.js') }}"></script>

    <script>
        let pages = [
            @foreach($pages as $page)
                "/pages/{{ $page }}",
            @endforeach
        ];
    </script>
    <script src="{{ asset('back/assets/js/pages/posters/create.js') }}"></script>
@endpush
