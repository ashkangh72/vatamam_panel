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

        .cancel-order-in-factor p {
            margin: 0;
        }

        .flex > * {
            float: left;
        }

        thead,
        tfoot {
            background: #eee;
        }

        .header-table table {
            width: 100%;
            vertical-align: middle;
        }

        .content-table {
            border-collapse: collapse;
        }

        .content-table td, th {
            border: 1px solid grey;
            text-align: center;
            padding: 0.1cm;
            font-weight: normal;
            color: #636363;
        }

        table.centered td {
            vertical-align: middle;
        }

        .title {
            text-align: center;
            line-height: 2;
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

        @page  {
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
    <h1 style="text-align: center; background-color: #96588A; padding: 30px; color: #FFFFFF; border-radius: 10px">
        سفارش جدید {{ $order->id }}#
    </h1>

    <h4 style="margin-right: 1rem; color: #96588A">
        شما یک سفارش از {{ $order->name }} دریافت کردید.
    </h4>
    <h4 style="margin-right: 1rem; color: #96588A">
        [سفارش #{{ $order->id }}]
        ({{ tverta($order->created_at)->format('%d %B %Y') }})
    </h4>

    <table class="content-table">
        <thead>
            <tr>
                <th style="width: 70%">محصول</th>
                <th style="width: 10%">تعداد</th>
                <th style="width: 20%">قیمت</th>
            </tr>
        </thead>
        <tbody>
        @foreach($order->items as $item)
            <tr>
                <td>
                    <div class="title font-medium">
                        {{ $item->title }}

                        <br>
                        @if ($item->get_price)
                            @foreach ($item->get_price->get_attributes as $attribute)

                                @if ($attribute->group->type == 'color')
                                    <span class="order-product-color d-print-none" style="background-color: {{ $attribute->value }};"></span>
                                    <span>{{ $attribute->group->name }}: {{ $attribute->name }}،</span>
                                @else
                                    <span>{{ $attribute->group->name }}: {{ $attribute->name }}،</span>
                                @endif

                            @endforeach
                        @endif
                        <span>انبار: {{ $item->product->warehouse->name }}</span>
                    </div>
                </td>
                <td><span class="ltr font-medium">{{ $item->quantity }}</span></td>
                <td><span class="ltr font-medium">{{ number_format($item->price * $item->quantity) }}</span></td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="font-small">
                    هزینه ارسال:
                </td>
                <td >
                    <span class="ltr font-medium">{{ number_format($order->shipping_cost) }}</span>
                </td>
            </tr>
            <tr>
                <td colspan="2" class="font-small">
                    جمع کل پس از تخفیف:
                </td>
                <td >
                    <span class="ltr font-medium">{{ number_format($order->price) }}</span>
                </td>
            </tr>
        </tfoot>
    </table>

    <h1 style="text-align: center; color: #96588A">
        آدرس خریدار
    </h1>
    <table class="content-table">
        <tr>
            <td style="width: 50%">
                <span class="label">خریدار:</span> {{ $order->name }}
            </td>
            <td style="width: 50%">
                <span class="label">شماره تماس:</span>{{ $order->mobile }}
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <span class="label">نشانی:</span>
                ایران،
                استان
                {{ $order->province ? $order->province->name : '' }}
                ، ‌شهر
                {{ $order->city ? $order->city->name : '' }}
                ،
                {{ $order->address }}
                <br>
                <span class="label">کد پستی:</span> {{ $order->postal_code }}
            </td>
        </tr>
    </table>

    <a class="btn-printPage">چاپ فاکتور</a>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
        integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('public/back/assets/js/jquery-barcode.min.js') }}"></script>

</body>
