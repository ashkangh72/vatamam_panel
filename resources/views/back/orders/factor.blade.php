<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>فاکتور فروش</title>
    <link rel="stylesheet" href="{{ asset('public/back/assets/fonts/iransansdn/style.css') }}">
    <style>
        html,
        body {
            padding: 0;
            margin: 0 auto;
            max-width: 29.7cm;
            -webkit-print-color-adjust: exact;
        }

        body {
            padding: 0.5cm
        }

        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        table {
            width: 100%;
            table-layout: fixed;
            border-spacing: 0;
        }

        .header-table {
            table-layout: fixed;
            border-spacing: 0;
        }

        .header-table td {
            padding: 0;
            vertical-align: top;
        }

        body {
            font: 9pt IransansDN;
            direction: rtl;
        }

        .page {
            background: white;
            position: relative;
            float: right;
            width: 100%;
        }

        .cancel-order-in-factor {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            right: 43%;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            border: 2px solid #f44336;
            color: #f44336;
            text-align: center;
            font-size: 40px;
            line-height: 60px;
        }

        .cancel-order-in-factor p {
            margin: 0;
        }

        .flex {
            display: flex;
        }

        .flex>* {
            float: left;
        }

        .flex-grow {
            flex-grow: 10000000;
        }

        .barcode {
            text-align: center;
            margin: 7px 0 0 0;
            height: 30px;
        }

        .barcode-generate,
        .barcode-generate-followup {
            display: inline-block
        }

        .portait {
            transform: rotate(-90deg) translate(0, 40%);
            text-align: center;
        }

        .header-item-wrapper {
            border: 1px solid #000;
            width: 100%;
            height: 100%;
            background: #eee;
            display: flex;
            align-content: center;
        }

        thead,
        tfoot {
            background: #eee;
        }

        .header-item-data {
            height: 100%;
            width: 100%;
        }

        .bordered {
            border: 1px solid #000;
            padding: 0.12cm;
        }

        .header-table table {
            width: 100%;
            vertical-align: middle;
        }

        .content-table {
            border-collapse: collapse;
        }

        .content-table td,
        th {
            border: 1px solid #000;
            text-align: center;
            padding: 0.1cm;
            font-weight: normal;
        }

        table.centered td {
            vertical-align: middle;
        }

        .serials {
            direction: ltr;
            text-align: left;
        }

        .title {
            text-align: right;
        }

        .grow {
            width: 100%;
            height: 100%;
        }

        .font-small {
            font-size: 8pt;
        }

        .font-medium {
            font-size: 10pt;
        }

        .font-big {
            font-size: 15pt;
        }

        .label {
            font-weight: bold;
            padding: 0 0 0 2px;
        }

        @page {
            size: A4 landscape;
            margin: 0;
            margin-bottom: 0.5cm;
            margin-top: 0.5cm;
        }

        .ltr {
            direction: ltr;
            display: block;
        }

        .btn-printPage {
            float: left;
            padding: 7px 15px;
            border: 2px solid #ccc;
            color: #979797;
            border-radius: 10px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="page">
        <h1 style="text-align: center">
            صورتحسـاب نیابتی فـروش كـالا و خـدمات
        </h1>
        <table class="header-table" style="width: 100%">
            <tr>
                <td style="width: 1.8cm; height: 2.5cm;vertical-align: middle;padding-bottom: 4px;">
                    <div class="header-item-wrapper">
                        <div class="portait" style="margin: 5px;">
                            فروشنده
                        </div>
                    </div>
                </td>
                <td style="padding: 0 4px 4px;height: 2cm;">
                    <div class="bordered grow header-item-data">
                        <table class="grow centered">
                            <tr>
                                <td style="width: 7cm">
                                    <span class="label">فروشنده:</span> {{ $order->seller->name }}
                                </td>
                                {{-- @if (option('factor_registeration_id'))
                                <td style="width: 5cm">
                                    <span class="label">شماره ثبت:</span> {{ option('factor_registeration_id') }}
                                </td>
                            @endif --}}
                                <td>
                                    <span class="label">نام کاربری:</span> {{ $order->seller->username }}
                                </td>
                                {{-- @if (option('factor_national_code'))
                                <td>
                                    <span class="label">شناسه ملی:</span> {{ option('factor_national_code') }}
                                </td>
                            @endif --}}
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <span class="label">نشانی:</span>
                                    {{ $order->seller->address ? $order->seller->address->province->name . ' - ' . $order->seller->address->city->name : '--' }}
                                </td>
                                <td>
                                    <span class="label">تلفن:</span> {{ $order->seller->phone }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
                <td style="width: 4.5cm;height: 2cm;padding: 0 0 4px;">
                    <div class="bordered grow" style="padding: 2mm 5mm;">
                        <div class="flex">
                            <div class="font-small">شماره فاکتور:</div>
                            <div class="flex-grow" style="text-align: left">{{ $order->id }}</div>
                        </div>
                        <br />
                        <div class="barcode">
                            <span class="barcode-generate">{{ $order->id }}</span>
                            <input type="hidden" id="codeFactor" value="{{ $order->id }}">
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="width: 1.8cm; height: 2cm;vertical-align: center; padding: 0 0 4px">
                    <div class="header-item-wrapper">
                        <div class="portait" style="margin: 20px">خریدار</div>
                    </div>
                </td>
                <td style="height: 2cm;vertical-align: center; padding: 0 4px 4px">
                    <div class="bordered header-item-data">
                        <table style="height: 100%" class="centered">
                            <tr>
                                <td style="width: 6.7cm">
                                    <span class="label">خریدار:</span>
                                    {{ $order->user->name }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <span class="label">نشانی:</span>
                                    ایران،
                                    استان
                                    {{ $order->address ? ($order->address->city ? $order->address->city->province->name : '--') : '--' }}
                                    ، ‌شهر
                                    {{ $order->address ? ($order->address->city ? $order->address->city->name : '--') : '--' }}
                                    ،
                                    {{ $order->address ? $order->address->address : '--' }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <span class="label">شماره
                                        تماس:</span>{{ $order->address ? $order->address->recipient_phone : '--' }}
                                </td>
                                <td colspan="2">
                                    <span class="label">کد پستی:</span>
                                    {{ $order->address ? $order->address->postal_code : '--' }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
                <td style="padding: 0 0 4px">
                    <div class="grow bordered" style="padding: 2mm 5mm;">
                        <div class="flex">
                            <div>تاریخ:</div>
                            <div class="flex-grow" style="text-align: left">
                                {{ tverta($order->created_at)->format('Y-m-d') }}</div>
                        </div>
                        <br />
                        <div class="flex">
                            <div>پیگیری:</div>
                            <div class="flex-grow font-medium" style="text-align: left">{{ $order->id }}</div>
                        </div>
                        <br />
                        <div class="barcode">
                            <span class="barcode-generate-followup">{{ $order->id }}</span>
                            <input type="hidden" id="codeFollowUp" value="{{ $order->id }}">
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        <table class="content-table">
            <thead>
                <tr>
                    <th style="width: 5%">ردیف</th>
                    <th style="width: 12%">کد کالا</th>
                    <th style="width: 40%">شرح کالا</th>
                    <th style="width: 5%">تعداد</th>
                    <th style="width: 15%">مبلغ واحد (تومان)</th>
                    <th style="width: 23%">مبلغ کل (تومان)</th>
                </tr>
            </thead>
            @foreach ($order->auctions as $auction)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $auction->sku }}</td>
                    <td>
                        <div class="title">{{ $auction->title }}</div>
                    </td>
                    <td><span class="ltr">{{ $auction->pivot->quantity }}</span></td>
                    <td><span class="ltr">{{ number_format($auction->price) }}</span></td>
                    <td><span
                            class="ltr">{{ number_format($auction->pivot->quantity * $auction->pivot->price) }}</span>
                    </td>
                </tr>
            @endforeach
            <tfoot>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="1" class="font-small">
                        هزینه ارسال (تومان):
                    </td>
                    <td>
                        <span class="ltr">{{ number_format($order->shipping_cost) }}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="1" class="font-small">
                        جمع کل پس از تخفیف (تومان):
                    </td>
                    <td>
                        <span class="ltr">{{ number_format($order->price) }}</span>
                    </td>
                </tr>
                <tr style="background: #fff">
                    <td colspan="11" style="height: 2.5cm;vertical-align: top">
                        <div class="flex">
                            <div class="flex-grow">مهر و امضای فروشنده:</div>
                            <div class="flex-grow">مهر و امضای خریدار:</div>
                        </div>
                        <div class="flex">
                            <div class="flex-grow"></div>
                            <div class="flex-grow"></div>
                        </div>
                    </td>
                </tr>
            </tfoot>
        </table>
        <a class="btn-printPage">چاپ فاکتور</a>

    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
        integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="{{ asset('public/back/assets/js/jquery-barcode.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            let valuecodeFactor = $("#codeFactor").val();
            let btypecodeFactor = "codabar";
            let settingscodeFactor = {
                barWidth: 1,
                barHeight: 25,
                showHRI: false,
            };
            $(".barcode-generate").barcode(
                valuecodeFactor, btypecodeFactor, settingscodeFactor
            );

            let valueFollowUp = $("#codeFollowUp").val();
            let btypeFollowUp = "codabar";
            let settingsFollowUp = {
                barWidth: 1,
                barHeight: 25,
                showHRI: false,
            };
            $(".barcode-generate-followup").barcode(
                valueFollowUp, btypeFollowUp, settingsFollowUp
            );
            $('a.btn-printPage').click(function() {
                window.print();
                return false;
            });
        });
    </script>
</body>
