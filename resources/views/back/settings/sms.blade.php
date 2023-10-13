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
                                                    <input type="text" name="admin_phone_number"
                                                           class="form-control ltr"
                                                           value="{{ option('admin_phone_number') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label>ایمیل مدیر برای ارسال اطلاع رسانی ها</label>
                                                <div class="input-group mb-75">
                                                    <input type="text" name="admin_email_address"
                                                           class="form-control ltr"
                                                           value="{{ option('admin_email_address') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <h3 class="my-2">تنظیمات ارسال پیامک</h3>
                                        <hr>
                                        <div class="form-group row">
                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_order_paid" type="checkbox"
                                                           name="sms_on_order_paid" {{ option('sms_on_order_paid') == 'on' ? 'checked' : '' }} >
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">اطلاع رسانی پرداخت سفارش به فروشنده</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_order_paid"
                                                          placeholder="{siteTitle} - پرداخت سفارش {newLine} سفارش شماره {orderId} توسط کاربر {userUsername} پرداخت شد"
                                                          rows="2">{{ option('sms_text_on_order_paid') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید, {siteTitle} : عنوان وبسایت, {orderId} : شناسه سفارش, {userUsername} : نام کاربری کاربر
                                                </small>
                                            </fieldset>
                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_auction_end" type="checkbox"
                                                           name="sms_on_auction_end" {{ option('sms_on_auction_end') == 'on' ? 'checked' : '' }} >
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">اطلاع رسانی پایان مزایده به فروشنده</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_auction_end"
                                                          placeholder="{siteTitle} - اتمام مزایده {newLine} مزایده {auctionTitle} به پایان رسید"
                                                          rows="2">{{ option('sms_text_on_auction_end') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید, {siteTitle} : عنوان وبسایت, {auctionTitle} : عنوان مزایده
                                                </small>
                                            </fieldset>
                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_auction_before_end" type="checkbox"
                                                           name="sms_on_auction_before_end" {{ option('sms_on_auction_before_end') == 'on' ? 'checked' : '' }} >
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">اطلاع رسانی قبل از پایان مزایده به فروشنده</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_auction_before_end"
                                                          placeholder="{siteTitle} - اتمام مزایده {newLine} مزایده {auctionTitle} تا نیم ساعت دیگر به پایان می رسد"
                                                          rows="2">{{ option('sms_text_on_auction_before_end') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید, {siteTitle} : عنوان وبسایت, {auctionTitle} : عنوان مزایده
                                                </small>
                                            </fieldset>
                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_thanks_for_buy" type="checkbox"
                                                           name="sms_on_thanks_for_buy" {{ option('sms_on_thanks_for_buy') == 'on' ? 'checked' : '' }} >
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">پیام تشکر از خرید به خریدار</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_thanks_for_buy"
                                                          placeholder="{siteTitle} - تشکر از خرید شما {newLine} بابت انتخاب و خرید شما از {siteTitle} سپاسگزاریم. سفارش شماره {orderId} دریافت شد"
                                                          rows="2">{{ option('sms_text_on_thanks_for_buy') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید, {siteTitle} : عنوان وبسایت, {orderId} : شناسه سفارش
                                                </small>
                                            </fieldset>
                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_winning_auction" type="checkbox"
                                                           name="sms_on_winning_auction" {{ option('sms_on_winning_auction') == 'on' ? 'checked' : '' }} >
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">اطلاع رسانی برنده شدن در مزایده به خریدار</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_winning_auction"
                                                          placeholder="{siteTitle} - برنده شدید! {newLine} تبریک! شما در مزایده {auctionTitle} برنده شدید. برای تسویه حساب 12 ساعت فرصت دارید"
                                                          rows="2">{{ option('sms_text_on_winning_auction') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید, {siteTitle} : عنوان وبسایت, {auctionTitle} : عنوان مزایده
                                                </small>
                                            </fieldset>
                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_auction_higher_bid" type="checkbox"
                                                           name="sms_on_auction_higher_bid" {{ option('sms_on_auction_higher_bid') == 'on' ? 'checked' : '' }} >
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">اطلاع رسانی پیشنهاد بالاتر در مزایده به خریدار</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_auction_higher_bid"
                                                          placeholder="{siteTitle} - پیشنهاد بالاتر {newLine} برای مزایده {auctionTitle} پیشنهاد بالاتری ثبت شد"
                                                          rows="2">{{ option('sms_text_on_auction_higher_bid') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید, {siteTitle} : عنوان وبسایت, {auctionTitle} : عنوان مزایده
                                                </small>
                                            </fieldset>
                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_followed_auction" type="checkbox"
                                                           name="sms_on_followed_auction" {{ option('sms_on_followed_auction') == 'on' ? 'checked' : '' }} >
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">اطلاع رسانی پایان مزایده دنبال شده به خریدار</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_followed_auction"
                                                          placeholder="{siteTitle} - اتمام مزایده {newLine} مزایده {auctionTitle} تا 3 ساعت دیگر به پایان می رسد"
                                                          rows="2">{{ option('sms_text_on_followed_auction') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید, {siteTitle} : عنوان وبسایت, {auctionTitle} : عنوان مزایده
                                                </small>
                                            </fieldset>
                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_notice_auction" type="checkbox"
                                                           name="sms_on_followed_auction" {{ option('sms_on_notice_auction') == 'on' ? 'checked' : '' }} >
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">اطلاع رسانی مزایده های موردعلاقه به خریدار</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_notice_auction"
                                                          placeholder="{siteTitle} - مزایده جدید {newLine} مزایده {auctionTitle} در دسته بندی موردعلاقه شما ایجاد شده است"
                                                          rows="2">{{ option('sms_text_on_notice_auction') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید, {siteTitle} : عنوان وبسایت, {auctionTitle} : عنوان مزایده
                                                </small>
                                            </fieldset>
                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_transaction" type="checkbox"
                                                           name="sms_on_transaction" {{ option('sms_on_transaction') == 'on' ? 'checked' : '' }} >
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">اطلاع رسانی تراکنش جدید به خریدار</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_transaction"
                                                          placeholder="{siteTitle} - تراکنش جدید {newLine} تراکنش جدیدی به مبلغ {transactionAmount} جزئیات: {transactionDescription} ایجاد شد"
                                                          rows="2">{{ option('sms_text_on_transaction') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید, {siteTitle} : عنوان وبسایت, {transactionAmount} : مبلغ تراکنش, {transactionDescription} : جزئیات تراکنش
                                                </small>
                                            </fieldset>
                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_discount" type="checkbox"
                                                           name="sms_on_discount" {{ option('sms_on_discount') == 'on' ? 'checked' : '' }} >
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">اطلاع رسانی کدتخفیف جدید به خریدار</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_discount"
                                                          placeholder="{siteTitle} - کد تخفیف جدید {newLine} کد تخفیف جدیدی با {discountType} {discountAmount} برای شما ایجاد شد"
                                                          rows="2">{{ option('sms_text_on_discount') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید, {siteTitle} : عنوان وبسایت, {discountType} : نوع تخفیف, {discountAmount} : مقدار تخفیف
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

@push('scripts')
    <script src="{{ asset('public/back/assets/js/pages/settings/sms.js') }}"></script>
@endpush
