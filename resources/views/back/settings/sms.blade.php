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

                                        {{-- <h3 class="my-2">اطلاعات اطلاع رسانی</h3>
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
                                        </div> --}}

                                        <h3 class="my-2">تنظیمات ارسال پیامک</h3>
                                        <hr>

                                        <span class="">فروشنده</span>
                                        <br />
                                        <br />

                                        <div class="form-group row">
                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_buy_product_to_seller" type="checkbox"
                                                        name="sms_on_buy_product_to_seller"
                                                        {{ option('sms_on_buy_product_to_seller') == 'on' ? 'checked' : '' }}>
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">اطلاع رسانی خرید محصول به فروشنده</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_buy_product_to_seller"
                                                    placeholder="کاربر گرامی {newLine} کالای شما {productTitle} در وب سایت وتمام توسط {buyerName} خریداری شد. لطفاْ جهت تکمیل مراحل فروش به پنل کاربری خود مراجعه کنید"
                                                    rows="2">{{ option('sms_text_on_buy_product_to_seller') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید, {productTitle} : عنوان کالا, {buyerName} : نام
                                                    کاربر
                                                </small>
                                            </fieldset>


                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_send_product_to_seller" type="checkbox"
                                                        name="sms_on_send_product_to_seller"
                                                        {{ option('sms_on_send_product_to_seller') == 'on' ? 'checked' : '' }}>
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">اطلاع رسانی ارسال کالا توسط خریدار به
                                                        فروشنده</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_send_product_to_seller"
                                                    placeholder="کاربر گرامی وتمام {newLine} کالای شما با کد رهگیری {trackingCode} ارسال شد." rows="2">{{ option('sms_text_on_send_product_to_seller') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید, {trackingCode} : کد رهگیری
                                                </small>
                                            </fieldset>


                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_satisfied_product_to_seller" type="checkbox"
                                                        name="sms_on_satisfied_product_to_seller"
                                                        {{ option('sms_on_satisfied_product_to_seller') == 'on' ? 'checked' : '' }}>
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">اطلاع رسانی تحویل کالا و رضایت خریدار به
                                                        فروشنده</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_satisfied_product_to_seller"
                                                    placeholder="فروشنده گرامی{newLine} کالای شما با موفقیت به خریدار تحویل داده شد و خریدار رضایت خود را از کالا اعلام کرده است{newLine}با تشکر از حسن معامله ی شما منتظر حضور مجدد شما در وتمام هستیم"
                                                    rows="2">{{ option('sms_text_on_satisfied_product_to_seller') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید
                                                </small>
                                            </fieldset>


                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_unsatisfied_product_to_seller" type="checkbox"
                                                        name="sms_on_unsatisfied_product_to_seller"
                                                        {{ option('sms_on_unsatisfied_product_to_seller') == 'on' ? 'checked' : '' }}>
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">اطلاع رسانی تحویل کالا و نارضایتی خریدار به
                                                        فروشنده</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_unsatisfied_product_to_seller"
                                                    placeholder="فروشنده گرامی{newLine} کالای شما به خریدار تحویل داده شد. اما خریدار از کالا اعلام نارضایتی کرد و مبلغ واریزی از صندوق امانات شماکسر شد. لطفا جهت پیگیری روند بازگشت کالا به وب سایت وتمام مراجعه نمایید."
                                                    rows="2">{{ option('sms_text_on_unsatisfied_product_to_seller') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید
                                                </small>
                                            </fieldset>


                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_win_auction_to_seller" type="checkbox"
                                                        name="sms_on_win_auction_to_seller"
                                                        {{ option('sms_on_win_auction_to_seller') == 'on' ? 'checked' : '' }}>
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">اطلاع رسانی خرید مزایده به فروشنده</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_win_auction_to_seller"
                                                    placeholder="وتمام!{newLine}کالای شما {auctionTitle} در مزایده خریداری شد! لطفا جهت تکمیل مراحل فروش و ارسال کالا به پنل کاربری خود مراجعه کنید"
                                                    rows="2">{{ option('sms_text_on_win_auction_to_seller') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید, {siteTitle} : عنوان وبسایت, {auctionTitle} : عنوان
                                                    مزایده
                                                </small>
                                            </fieldset>


                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_end_auction_to_seller" type="checkbox"
                                                        name="sms_on_end_auction_to_seller"
                                                        {{ option('sms_on_end_auction_to_seller') == 'on' ? 'checked' : '' }}>
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">اطلاع رسانی پایان مزایده به فروشنده</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_end_auction_to_seller"
                                                    placeholder="کاربر گرامی وتمام!{newLine}زمان مزایده شما {auctionTilte} به اتمام رسید متاسفانه پیشنهادی به حداقل میزان مبلغ فروش نرسید لطفا جهت ثبت مجدد یا ویرایش مزایده خود به پنل کاربری خود مراجعه کنید"
                                                    rows="2">{{ option('sms_text_on_end_auction_to_seller') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید, {auctionTitle}: عنوان مزایده
                                                </small>
                                            </fieldset>
                                        </div>

                                        <span class="">خریدار</span>
                                        <br />
                                        <br />

                                        <div class="form-group row">
                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_send_product_to_buyer" type="checkbox"
                                                        name="sms_on_send_product_to_buyer"
                                                        {{ option('sms_on_send_product_to_buyer') == 'on' ? 'checked' : '' }}>
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">اطلاع رسانی ارسال کالا توسط فروشنده به
                                                        خریدار</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_send_product_to_buyer"
                                                    placeholder="کاربر گرامی وتمام {newLine} کالای شما {trackingCode} ارسال شد." rows="2">{{ option('sms_text_on_send_product_to_buyer') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید, {trackingCode} : ...با کد رهگیری
                                                </small>
                                            </fieldset>


                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_accept_unsatisfied_product_to_buyer"
                                                        type="checkbox" name="sms_on_accept_unsatisfied_product_to_buyer"
                                                        {{ option('sms_on_accept_unsatisfied_product_to_buyer') == 'on' ? 'checked' : '' }}>
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">اطلاع رسانی وضعیت اعلام نارضایتی به
                                                        خریدار</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_accept_unsatisfied_product_to_buyer"
                                                    placeholder="کاربر گرامی وتمام {newLine} اعلام نارضایتی شما توسط کارشناسان وتمام بررسی شد. جهت تکمیل مراحل خرید به پنل کاربری خود مراجعه کنید"
                                                    rows="2">{{ option('sms_text_on_accept_unsatisfied_product_to_buyer') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید
                                                </small>
                                            </fieldset>


                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_win_auction_to_buyer" type="checkbox"
                                                        name="sms_on_win_auction_to_buyer"
                                                        {{ option('sms_on_win_auction_to_buyer') == 'on' ? 'checked' : '' }}>
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">اطلاع رسانی برنده شدن در مزایده به
                                                        خریدار</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_win_auction_to_buyer"
                                                    placeholder="کاربر گرامی وتمام {newLine} شما در مزایده {auctionTitle} برنده شدید. جهت تکمیل مراحل خرید به پنل کاربری خود مراجعه کنید"
                                                    rows="2">{{ option('sms_text_on_win_auction_to_buyer') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید, {auctionTitle} : عنوان مزایده
                                                </small>
                                            </fieldset>


                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_buy_product_to_buyer" type="checkbox"
                                                        name="sms_on_buy_product_to_buyer"
                                                        {{ option('sms_on_buy_product_to_buyer') == 'on' ? 'checked' : '' }}>
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">اطلاع رسانی خرید محصول به خریدار</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_buy_product_to_buyer"
                                                    placeholder="کاربر گرامی {newLine} کالای {productTitle} با موفقیت خریداری شد. با ارسال کالا توسط فروشنده به شما اطلاع رسانی خواهد شد."
                                                    rows="2">{{ option('sms_text_on_buy_product_to_buyer') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید, {productTitle} : عنوان کالا, {buyerName} : نام
                                                    کاربر
                                                </small>
                                            </fieldset>
                                        </div>


                                        <span class="">درج کالا</span>
                                        <br />
                                        <br />

                                        <div class="form-group row">
                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_accept_product_to_seller" type="checkbox"
                                                        name="sms_on_accept_product_to_seller"
                                                        {{ option('sms_on_accept_product_to_seller') == 'on' ? 'checked' : '' }}>
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">اطلاع رسانی تایید محصول درج شده به فروشنده</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_accept_product_to_seller"
                                                    placeholder="کاربر گرامی وتمام {newLine} کالای شما {productTitle} در گالری آنلاین تایید و ثبت شد. {newLine}با تشکر از همراهی شما{newLine}منتظر خریداران وتمام باشید!."
                                                    rows="2">{{ option('sms_text_on_accept_product_to_seller') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید, {productTitle} : عنوان کالا
                                                </small>
                                            </fieldset>


                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_accept_auction_to_seller" type="checkbox"
                                                        name="sms_on_accept_auction_to_seller"
                                                        {{ option('sms_on_accept_auction_to_seller') == 'on' ? 'checked' : '' }}>
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">اطلاع رسانی تایید مزایده درج شده به فروشنده</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_accept_auction_to_seller"
                                                    placeholder="کاربر گرامی وتمام {newLine} مزایده شما {auctionTitle} در گالری آنلاین تایید و ثبت شد. {newLine}با تشکر از همراهی شما{newLine}منتظر خریداران وتمام باشید!."
                                                    rows="2">{{ option('sms_text_on_accept_auction_to_seller') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید, {auctionTitle} : عنوان مزایده
                                                </small>
                                            </fieldset>


                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_reject_product_to_seller" type="checkbox"
                                                        name="sms_on_reject_product_to_seller"
                                                        {{ option('sms_on_reject_product_to_seller') == 'on' ? 'checked' : '' }}>
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">اطلاع رسانی رد محصول درج شده به فروشنده</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_reject_product_to_seller"
                                                    placeholder="کاربر گرامی وتمام {newLine} کالای شما {productTitle} در گالری آنلاین رد شد. {newLine}جهت ویرایش کالای خود به پنل کاربری مراجعه کنید.{newLine}دلیل رد: {reason}"
                                                    rows="2">{{ option('sms_text_on_reject_product_to_seller') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید, {productTitle} : عنوان کالا, {reason} : دلیل رد
                                                </small>
                                            </fieldset>


                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_reject_auction_to_seller" type="checkbox"
                                                        name="sms_on_reject_auction_to_seller"
                                                        {{ option('sms_on_reject_auction_to_seller') == 'on' ? 'checked' : '' }}>
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">اطلاع رسانی رد مزایده درج شده به فروشنده</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_reject_auction_to_seller"
                                                    placeholder="کاربر گرامی وتمام {newLine} مزایده شما {auctionTitle} در گالری آنلاین رد شد. {newLine}جهت ویرایش کالای خود به پنل کاربری مراجعه کنید.{newLine}دلیل رد: {reason}"
                                                    rows="2">{{ option('sms_text_on_reject_auction_to_seller') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید, {auctionTitle} : عنوان مزایده, {reason} : دلیل رد
                                                </small>
                                            </fieldset>
                                        </div>

                                        <span class="">سرویس گوش به زنگ و تراکنش های مالی</span>
                                        <br />
                                        <br />

                                        <div class="form-group row">
                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_notice_auction" type="checkbox"
                                                        name="sms_on_notice_auction"
                                                        {{ option('sms_on_notice_auction') == 'on' ? 'checked' : '' }}>
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">اطلاع رسانی سرویس گوش به زنگ</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_notice_auction"
                                                    placeholder="{siteTitle} - کالای جدید {newLine} کالای {auctionTitle} در دسته بندی موردعلاقه شما ایجاد شده است"
                                                    rows="2">{{ option('sms_text_on_notice_auction') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید, {auctionTitle} : عنوان کالا
                                                </small>
                                            </fieldset>


                                            <fieldset class="checkbox col-md-6 col-12">
                                                <div class="vs-checkbox-con vs-checkbox-primary">
                                                    <input data-class="sms_on_send_product_to_buyer" type="checkbox"
                                                        name="sms_on_send_product_to_buyer"
                                                        {{ option('sms_on_send_product_to_buyer') == 'on' ? 'checked' : '' }}>
                                                    <span class="vs-checkbox">
                                                        <span class="vs-checkbox--check">
                                                            <i class="vs-icon feather icon-check"></i>
                                                        </span>
                                                    </span>
                                                    <span class="">اطلاع رسانی تراکنش های مالی</span>
                                                </div>
                                                <textarea class="form-control" name="sms_text_on_transaction"
                                                    placeholder="{siteTitle} - تراکنش جدید {newLine} تراکنش جدیدی به مبلغ {transactionAmount} جزئیات: {transactionDescription} ایجاد شد"
                                                    rows="2">{{ option('sms_text_on_transaction') }}</textarea>
                                                <small class="form-text text-muted mb-2">
                                                    {newLine} : خط جدید, {transactionAmount} : مقدار, {transactionDescription}: توضیحات
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
