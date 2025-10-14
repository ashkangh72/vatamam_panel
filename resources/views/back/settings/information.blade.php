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
                                    <li class="breadcrumb-item">تنظیمات</li>
                                    <li class="breadcrumb-item active">تنظیمات کلی</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- users edit start -->
                <section class="users-edit">
                    <div class="card">
                        <div id="main-card" class="card-content">
                            <div class="card-body">

                                <div class="tab-content">

                                    <form id="information-form" action="{{ route('admin.settings.information') }}"
                                        method="POST">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <label>عنوان وبسایت</label>
                                                        <input type="text" name="info_site_title" class="form-control"
                                                            value="{{ option('info_site_title') }}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <label>عنوان در فاکتور</label>
                                                        <input type="text" name="factor_title" class="form-control"
                                                            value="{{ option('factor_title', option('info_site_title')) }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <fieldset class="form-group">
                                                    <label for="basicInputFile">لوگو</label>
                                                    <div class="custom-file">
                                                        <input type="file" accept="image/*" name="info_logo"
                                                            class="custom-file-input">
                                                        <label class="custom-file-label"
                                                            for="inputGroupFile01">{{ option('info_logo') ? env('API_URL') . '/public' . option('info_logo') : '' }}</label>
                                                        <p><small>بهترین اندازه <span class="text-danger">500*500</span>
                                                                پیکسل میباشد.</small></p>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-2">
                                                <img class="mt-3"
                                                    src="{{ option('info_logo') ? env('API_URL') . '/public' . option('info_logo') : '' }}"
                                                    alt="لوگو" width="120px">
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <label>تلفن</label>
                                                        <input type="text" name="info_phone" class="form-control"
                                                            value="{{ option('info_phone') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <label>شماره اقتصادی</label>
                                                        <input type="text" name="factor_economical_number"
                                                            class="form-control"
                                                            value="{{ option('factor_economical_number') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <label>شناسه ملی</label>
                                                        <input type="text" name="factor_national_code"
                                                            class="form-control"
                                                            value="{{ option('factor_national_code') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <label>شناسه ثبت</label>
                                                        <input type="text" name="factor_registeration_id"
                                                            class="form-control"
                                                            value="{{ option('factor_registeration_id') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <label>ایمیل</label>
                                                        <input type="text" name="info_email" class="form-control"
                                                            value="{{ option('info_email') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <fieldset class="form-group">
                                                    <label>آدرس</label>
                                                    <textarea name="info_address" class="form-control" rows="3">{{ option('info_address') }}</textarea>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-6">
                                                <fieldset class="form-group">
                                                    <label>متن کپی رایت</label>
                                                    <textarea name="info_copy_right" class="form-control" rows="3">{{ option('info_copy_right') }}</textarea>
                                                </fieldset>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <fieldset class="form-group">
                                                    <label>اسکریپت نماد</label>
                                                    <textarea name="info_enamad" class="form-control ltr" rows="3">{{ option('info_enamad') }}</textarea>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-6">
                                                <fieldset class="form-group">
                                                    <label>کد ساماندهی</label>
                                                    <textarea name="info_samandehi" class="form-control ltr" rows="3">{{ option('info_samandehi') }}</textarea>
                                                </fieldset>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <fieldset class="form-group">
                                                    <label for="basicInputFile">لوگوی اتحادیه کسب و کار</label>
                                                    <div class="custom-file">
                                                        <input type="file" accept="image/*" name="logo_kasbokar"
                                                            class="custom-file-input">
                                                        <label class="custom-file-label"
                                                            for="inputGroupFile01">{{ option('logo_kasbokar') ? env('API_URL') . '/public' . option('logo_kasbokar') : '' }}</label>
                                                        <p><small>بهترین اندازه <span class="text-danger">500*500</span>
                                                                پیکسل میباشد.</small></p>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-4">
                                                <fieldset class="form-group">
                                                    <label for="basicInputFile">لینک اتحادیه کسب و کار</label>
                                                    <div class="custom-file">
                                                        <input type="text" class="form-control" name="kasbokar" value="{{option('kasbokar')}}">
                                                        <p><small>بهترین اندازه <span class="text-danger">500*500</span>
                                                                پیکسل میباشد.</small></p>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="col-md-2">
                                                <img class="mt-3"
                                                    src="{{ option('logo_kasbokar') ? env('API_URL') . '/public' . option('logo_kasbokar') : '' }}"
                                                    alt="لوگوی اتحادیه کسب و کار" width="120px">
                                            </div>
                                        </div>

                                        <div class="row p-0">
                                            <div class="col-12 mt-2">
                                                <button type="submit" class="btn btn-primary glow btn-block">ذخیره
                                                    تغییرات</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- users edit ends -->
            </div>
        </div>
    </div>
@endsection

@include('back.partials.plugins', ['plugins' => ['jquery.validate']])

@push('scripts')
    <script src="{{ asset('public/back/assets/js/pages/settings/information.js') }}"></script>
    <script src="{{ asset('public/back/app-assets/js/scripts/navs/navs.js') }}"></script>
@endpush
