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
                                    <li class="breadcrumb-item">مدیریت
                                    </li>
                                    <li class="breadcrumb-item active">ویرایش پروفایل
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="content-body">
                <section  class="card">
                    <div class="card-header">
                        <h4 class="card-title">ویرایش پروفایل</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-10 offset-md-1">
                                    <form id="profile-form" action="{{ route('admin.user.profile.update') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                                        @csrf
                                        @method('put')
                                        <div class="row">
                                            <input type="file" name="image" id="profile-image" accept="image/*" style="display: none;">
                                            <div class="col-12 col-sm-6">
                                                <div class="form-row">
                                                    <div class="form-group col-md-12">
                                                        <div class="controls">
                                                            <label>نام</label>
                                                            <input name="name" type="text" class="form-control" placeholder="نام" value="{{ auth()->user()->name }}" required >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <label>نام کاربری</label>
                                                        <input disabled name="username" type="text" class="form-control" placeholder="نام کاربری" value="{{ auth()->user()->username }}" required >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6">
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <label>گذرواژه</label>
                                                        <input id="password" name="password" type="text" class="form-control pw" placeholder="گذرواژه">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <label>تکرار گذرواژه</label>
                                                        <input name="password_confirmation" type="text" class="form-control pw" placeholder="تکرار گذرواژه">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <label>رنگ سایدبار</label>
                                                        <select name="theme_color" class="form-control">
                                                            <option value="light" {{ user_option('theme_color') == 'light' ? 'selected' : '' }}>سفید</option>
                                                            <option value="dark" {{ user_option('theme_color') == 'dark' ? 'selected' : '' }}>مشکی</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <label>فونت</label>
                                                        <select name="theme_font" class="form-control">
                                                            <option value="Vazir" {{ user_option('theme_font') == 'Vazir' ? 'selected' : '' }}>Vazir</option>
                                                            <option value="IransansDN" {{ user_option('theme_font') == 'IransansDN' ? 'selected' : '' }}>IransansDN</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="controls">
                                                        <label>نوع منو</label>
                                                        <select name="menu_type" class="form-control">
                                                            <option value="normal" {{ user_option('menu_type') == 'normal' ? 'selected' : '' }}>معمولی</option>
                                                            <option value="collapsed" {{ user_option('menu_type') == 'collapsed' ? 'selected' : '' }}>جمع شده</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
{{--                                            <div class="col-12">--}}
{{--                                                <div class="custom-control custom-switch custom-control-inline">--}}
{{--                                                    <input type="checkbox" class="custom-control-input" id="subscribe-to-webpush" {{ auth()->user()->pushSubscriptions()->first() ? 'checked' : '' }}>--}}
{{--                                                    <label class="custom-control-label" for="subscribe-to-webpush">--}}
{{--                                                    </label>--}}
{{--                                                    <span class="switch-label">دریافت نوتیفیکیشن</span>--}}

{{--                                                </div>--}}
{{--                                            </div>--}}
                                            <div class="col-12">
                                                <div class="alert alert-info mt-1 alert-validation-msg" role="alert">
                                                    <i class="feather icon-info ml-1 align-middle"></i>
                                                    <span>در صورتی که نمیخواهید گذرواژه تان را عوض کنید، فیلدهای گذرواژه را خالی بگذارید.</span>
                                                </div>
                                            </div>
                                            <div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
                                                <button type="submit" class="btn btn-primary glow mb-1 mb-sm-0 mr-0 mr-sm-1">ذخیره تغییرات</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>


            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('public/back/app-assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('public/back/app-assets/plugins/jquery-validation/localization/messages_fa.min.js') }}"></script>
    <script src="{{ asset('public/back/assets/js/pages/users/profile.js') }}"></script>
@endpush
