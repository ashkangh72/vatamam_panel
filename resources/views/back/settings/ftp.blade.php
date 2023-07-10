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
                                    <li class="breadcrumb-item">تنظیمات
                                    </li>
                                    <li class="breadcrumb-item active">تنظیمات ftp
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- ftp edit start -->
                <section class="users-edit">
                    <div class="card">
                        <div id="main-card" class="card-content">
                            <div class="card-body">
                                <div class="tab-content">
                                    <form id="ftp-form" action="{{ route('admin.settings.ftp') }}" method="POST">
                                        <div class="row">
                                            <div class="form-group col-md-2">
                                                <fieldset class="checkbox">
                                                    <div class="vs-checkbox-con vs-checkbox-primary">
                                                        <input data-class="ftp" type="checkbox" name="FTP_ACTIVE" {{ config('general.ftp.active', false) ? 'checked' : '' }} >
                                                        <span class="vs-checkbox">
                                                            <span class="vs-checkbox--check">
                                                                <i class="vs-icon feather icon-check"></i>
                                                            </span>
                                                        </span>
                                                        <span class="">سرور ftp</span>
                                                    </div>
                                                </fieldset>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <fieldset class="checkbox">
                                                    <div class="vs-checkbox-con vs-checkbox-primary">
                                                        <input class="ftp" type="checkbox" name="FTP_SSL" {{ config('general.ftp.ssl', false) ? 'checked' : '' }} >
                                                        <span class="vs-checkbox">
                                                            <span class="vs-checkbox--check">
                                                                <i class="vs-icon feather icon-check"></i>
                                                            </span>
                                                        </span>
                                                        <span class="">اتصال با SSL</span>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 form-group">
                                                <label>آدرس / آیپی</label>
                                                <div class="input-group mb-75">
                                                    <input type="text" name="FTP_HOST" class="form-control ltr ftp"
                                                           value="{{ config('general.ftp.host') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label>پورت</label>
                                                <div class="input-group mb-75">
                                                    <input type="text" name="FTP_PORT" class="form-control ltr ftp"
                                                           value="{{ config('general.ftp.port', 21) }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label>ریشه</label>
                                                <div class="input-group mb-75">
                                                    <input type="text" name="FTP_ROOT" class="form-control ltr ftp"
                                                           value="{{ config('general.ftp.root', '') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label>نام کاربری</label>
                                                <div class="input-group mb-75">
                                                    <input type="password" name="FTP_USERNAME" class="form-control ltr ftp"
                                                           value="{{ config('general.ftp.username') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label>رمزعبور</label>
                                                <div class="input-group mb-75">
                                                    <input type="password" name="FTP_PASSWORD" class="form-control ltr ftp"
                                                           value="{{ config('general.ftp.password') }}" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="alert alert-info mt-1 alert-validation-msg" role="alert">
                                                    <i class="feather icon-info ml-1 align-middle"></i>
                                                    <span>برای فعال نمودن ftp پس از انتخاب، اطلاعات مربوط به آن را پر کنید.</span>
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
                <!-- ftp edit ends -->
            </div>
        </div>
    </div>

@endsection

@include('back.partials.plugins', ['plugins' => ['jquery.validate']])

@push('scripts')
    <script src="{{ asset('public/back/assets/js/pages/settings/ftp.js') }}?v=1"></script>
@endpush
