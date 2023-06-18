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
                                    <li class="breadcrumb-item active">تنظیمات پیامک
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- settings edit start -->
                <section class="users-edit">
                    <div class="card">
                        <div id="main-card" class="card-content">
                            <div class="card-body">
                                <div class="tab-content">
                                    <form id="sms-form" action="{{ route('admin.settings.sms') }}" method="POST">

                                        <h3 class="my-2">اطلاعات اطلاع رسانی</h3>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>شماره تلفن مدیر برای ارسال اطلاع رسانی ها</label>
                                                <div class="input-group mb-75">
                                                    <input type="text" name="admin_mobile_number"
                                                           class="form-control ltr"
                                                           value="{{ option('admin_mobile_number') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label>ایمیل  مدیر برای ارسال اطلاع رسانی ها</label>
                                                <div class="input-group mb-75">
                                                    <input type="text" name="admin_email_address"
                                                           class="form-control ltr"
                                                           value="{{ option('admin_email_address') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <h3 class="my-2">اطلاعات پنل پیامک sms.ir</h3>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>کد api</label>
                                                <div class="input-group mb-75">
                                                    <input type="text" name="SMS_PANEL_API_KEY" class="form-control ltr"
                                                           value="{{ option('SMS_PANEL_API_KEY') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label>کد secret</label>
                                                <div class="input-group mb-75">
                                                    <input type="text" name="SMS_PANEL_SECRET_KEY"
                                                           class="form-control ltr"
                                                           value="{{ option('SMS_PANEL_SECRET_KEY') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label>شماره خط</label>
                                                <div class="input-group mb-75">
                                                    <input type="text" name="SMS_PANEL_LINE_NUMBER"
                                                           class="form-control ltr"
                                                           value="{{ option('SMS_PANEL_LINE_NUMBER') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label>پترن ارسال کد تایید</label>
                                                <div class="input-group mb-75">
                                                    <input type="text" name="user_verify_pattern_code"
                                                           class="form-control ltr"
                                                           value="{{ option('user_verify_pattern_code') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <h3 class="my-2">تنظیمات ارسال پیامک</h3>
                                        <hr>
                                        <div class="form-group row">
                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_user_register" type="checkbox"
                                                           name="sms_on_user_register" {{ option('sms_on_user_register') == 'on' ? 'checked' : '' }} >
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">خوش آمد گویی به کاربر</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_user_register"
                                                          placeholder="{{ config('app.name') }} {newLine} {name} عزیز خوش آمدید"
                                                          rows="2">{{ option('sms_text_on_user_register') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید, {name} : نام کاربر
                                                </small>
                                            </fieldset>
                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_order_not_paid" type="checkbox"
                                                           name="sms_on_order_not_paid" {{ option('sms_on_order_not_paid') == 'on' ? 'checked' : '' }} >
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">یادآوری پرداخت سفارش به کاربر</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_order_not_paid"
                                                          placeholder="{{ config('app.name') }} {newLine} شما یک سفارش پرداخت نشده دارید. در صورت عدم پرداخت، سفارش شما تا {after} دقیقه دیگر لغو میشود {newLine} {url}"
                                                          rows="2">{{ option('sms_text_on_order_not_paid') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید, {url} : لینک مستقیم سفارش, {after} : زمان تا لغو(30)
                                                </small>
                                            </fieldset>
                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_order_paid" type="checkbox"
                                                           name="sms_on_order_paid" {{ option('sms_on_order_paid') == 'on' ? 'checked' : '' }} >
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">اطلاع رسانی پرداخت سفارش به کاربر</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_order_paid"
                                                          placeholder="{{ config('app.name') }} {newLine} سفارش جدید ثبت و پرداخت شد. {newLine} مبلغ سفارش {orderPrice} {newLine} جزئیات سفارش {url}"
                                                          rows="2">{{ option('sms_text_on_order_paid') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید, {orderPrice} : هزینه سفارش, {url} : لینک مستقیم سفارش
                                                </small>
                                            </fieldset>
                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="admin_sms_on_order_paid" type="checkbox"
                                                           name="admin_sms_on_order_paid" {{ option('admin_sms_on_order_paid') == 'on' ? 'checked' : '' }} >
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">اطلاع رسانی پرداخت سفارش به مدیر</span>
                                                </div>
                                                <textarea class="form-control" name="admin_sms_text_on_order_paid"
                                                          placeholder="{{ config('app.name') }} {newLine} سفارش {orderId} ثبت شد. مبلغ سفارش {orderPrice}"
                                                          rows="2">{{ option('admin_sms_text_on_order_paid') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید, {orderId} : شناسه سفارش, {orderPrice} : هزینه سفارش
                                                </small>
                                            </fieldset>
                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_order_status_changed" type="checkbox"
                                                           name="sms_on_order_status_changed" {{ option('sms_on_order_status_changed') == 'on' ? 'checked' : '' }} >
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">اطلاع رسانی تغییر وضعیت سفارش به کاربر</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_order_status_changed"
                                                          placeholder="{{ config('app.name') }} {newLine} وضعیت سفارش شما به {status} تغییر کرد {newLine} {url}"
                                                          rows="2">{{ option('sms_text_on_order_status_changed') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید, {status} : وضعیت سفارش, {url} : لینک مستقیم سفارش
                                                </small>
                                            </fieldset>
                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_unbound_cart" type="checkbox"
                                                           name="sms_on_unbound_cart" {{ option('sms_on_unbound_cart') == 'on' ? 'checked' : '' }} >
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">اطلاع رسانی سبدخرید رهاشده به کاربر</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_unbound_cart"
                                                          placeholder="{{ config('app.name') }} {newLine} شما یک سبد خرید پرداخت نشده دارید {newLine} {url}"
                                                          rows="2">{{ option('sms_text_on_unbound_cart') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید, {url} : لینک مستقیم سبدخرید
                                                </small>
                                            </fieldset>
                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_stock_notify" type="checkbox"
                                                           name="sms_on_stock_notify" {{ option('sms_on_stock_notify') == 'on' ? 'checked' : '' }} >
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">اطلاع رسانی موجودشدن محصول به کاربر</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_stock_notify"
                                                          placeholder="{{ config('app.name') }} {newLine} محصول {productTitle} موجود شد {newLine} {url}"
                                                          rows="2">{{ option('sms_text_on_stock_notify') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید, {productTitle} : نام محصول, {url} : لینک مستقیم محصول
                                                </small>
                                            </fieldset>
                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_refund_requests" type="checkbox"
                                                           name="sms_on_refund_requests" {{ option('sms_on_refund_requests') == 'on' ? 'checked' : '' }} >
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">اطلاع رسانی درخواست بازگردانی به کاربر</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_refund_requests"
                                                          placeholder="{{ config('app.name') }} {newLine} وضعیت درخواست بازگردانی شما به {status} تغیر کرد {newLine} {url}"
                                                          rows="2">{{ option('sms_text_on_refund_requests') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید, {status} : وضعیت درخواست بازگردانی, {url} : لینک مستقیم سفارش
                                                </small>
                                            </fieldset>
                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_marketing_commission_deposit_request_status_changed" type="checkbox"
                                                           name="sms_on_marketing_commission_deposit_request_status_changed" {{ option('sms_on_marketing_commission_deposit_request_status_changed') == 'on' ? 'checked' : '' }} >
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">اطلاع رسانی تغییر وضعیت درخواست برداشت کمیسیون کمپین به کاربر</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_marketing_commission_deposit_request_status_changed"
                                                          placeholder="{{ config('app.name') }} {newLine} وضعیت درخواست برداشت کمیسیون کمپین {campaignName} به {status} تغییر کرد {newLine} {url}"
                                                          rows="2">{{ option('sms_text_on_marketing_commission_deposit_request_status_changed') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید, {url} : لینک مستقیم کیف پول, {campaignName} : نام کمپین, {status} : وضعیت درخواست برداشت کمیسیون کمپین
                                                </small>
                                            </fieldset>
                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_marketing_request_status_changed" type="checkbox"
                                                           name="sms_on_marketing_request_status_changed" {{ option('sms_on_marketing_request_status_changed') == 'on' ? 'checked' : '' }} >
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">اطلاع رسانی تغییر وضعیت درخواست بازاریابی به کاربر</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_marketing_request_status_changed"
                                                          placeholder="{{ config('app.name') }} {newLine} وضعیت درخواست بازاریابی شما به {status} تغییر کرد {newLine} {url}"
                                                          rows="2">{{ option('sms_text_on_marketing_request_status_changed') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید, {url} : لینک مستقیم کیف پول, {status} : وضعیت درخواست بازاریابی
                                                </small>
                                            </fieldset>
                                        </div>

                                        <div class="row">
                                            <div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
                                                <button type="submit" class="btn btn-primary glow">ذخیره تغییرات</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- settings edit ends -->

            </div>
        </div>
    </div>

@endsection

@include('back.partials.plugins', ['plugins' => ['jquery.validate']])

@php
    $help_videos = [
        config('general.video-helpes.sms-config')
    ];
@endphp

@push('scripts')
    <script src="{{ asset('back/assets/js/pages/settings/sms.js') }}"></script>
@endpush
