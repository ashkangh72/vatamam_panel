@foreach($orders as $order)
<!DOCTYPE html>
<html dir="rtl" lang="fa-IR">
<head>
    <title>فاکتور فروش</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1.0, user-scalable=no">
    <style>
        pre.xdebug-var-dump {
            direction: ltr;
            text-align: left;
            font-family: monospace;
        }

        @page {
            size: A4 portrait;
            margin: 0;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
        }

        @media print {
            html, body {
                height: 99%;
                page-break-after: avoid;
                page-break-before: avoid;
            }
        }

        * {
            box-sizing: border-box;
        }

        html, body, div, span, object, iframe,
        h1, h2, h3, h4, h5, h6, p, blockquote, pre,
        abbr, address, cite, code, del, dfn, em,
        img, ins, kbd, q, samp, small, strong, sub,
        sup, var, b, i, dl, dt, dd, ol, ul, li,
        fieldset, form, label, legend, table, caption,
        tbody, tfoot, thead, tr, th, td, article,
        aside, canvas, details, figcaption, figure,
        footer, header, hgroup, menu, nav, section,
        summary, time, mark, audio, video {
            margin: 0;
            padding: 0;
            border: 0;
            outline: 0;
            font-size: 100%;
            vertical-align: baseline;
            background: transparent;
        }

        body {
            line-height: 24px;
            color: #000;
            font-size: 16px;
            font-family: Tahoma, Arial, sans-serif;
        }

        article, aside, details, figcaption, figure,
        footer, header, hgroup, menu, nav, section {
            display: block;
        }

        a {
            color: #000;
            margin: 0;
            padding: 0;
            font-size: 100%;
            vertical-align: baseline;
            background: transparent;
            text-decoration: none;
        }

        ul {
            list-style: none;
        }

        ins {
            text-decoration: none;
            display: block;
        }

        del {
            text-decoration: line-through;
            display: block;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        table {
            border-collapse: collapse;
            border-spacing: 0;
            width: 100%;
            max-width: 100%;
        }

        table th, table td {
            vertical-align: middle;
            padding: 10px;
        }

        table.table-fixed {
            table-layout: fixed;
        }


        .print {
            position: fixed;
            left: 4px;
            top: 4px;
            display: flex;
            flex-direction: column;
        }

        @media print {
            .print {
                display: none;
            }
        }

        .button {
            background: #FF6348;
            color: #FFF;
            text-align: center;
            border-radius: 2px;
            line-height: 46px;
            cursor: pointer;
            display: inline-block;
            width: 40px;
            height: 40px;
        }

        @media (max-width: 576px) {
            .table-responsive thead {
                display: none;
            }

            .table-responsive td {
                width: 100%;
                display: block;
            }
        }

        .table-bordered {
            border: 1px solid #000;
        }

        @media (min-width: 576px) {
            .table-bordered th, .table-bordered td {
                border: 1px solid #000;
            }
        }

        @media (max-width: 576px) {
            .table-bordered tbody tr:not(:last-child) td:last-child {
                border-bottom: 1px solid #000;
            }
        }

        @media (max-width: 576px) {
            .total-table tbody tr:not(:last-child) td:last-child {
                display: table-cell;
                width: auto;
            }

            .total-table tbody tr:not(:last-child) th {
                border-bottom: 1px solid #000;
            }
        }

        .clearfix:after {
            content: '';
            display: block;
            clear: both;
        }

        .container {
            position: relative;
            border: 1px solid transparent;
            padding: 10px;
        }

        .component {
            margin: 0 5px;
        }

        .component .title {
            font-weight: bold;
            display: inline-block;
            margin: 0 5px;
        }

        .component .content {
            display: inline-block;
        }

        .barcode > * {
            margin: auto;
        }

        .barcode desc {
            display: none !important;
        }

        @media (min-width: 576px) {
            .products-table td .td-title {
                display: none;
            }
        }

        .products-table img.wp-post-image {
            max-width: 100px;
        }

        .products-table .product-attributes li {
            display: inline-block;
        }

        @media (max-width: 576px) {
            .products-table .row {
                display: none;
            }

            .products-table td {
                min-height: 54px;
                text-align: right;
            }

            .products-table td .td-title {
                font-weight: bold;
                float: left;
                margin-right: 5px;
            }

            .products-table tbody tr.even td {
                background: rgba(146, 146, 146, 0.25);
            }

            .products-table tfoot {
                display: none;
            }

            .rtl .products-table td {
                text-align: left;
            }

            .rtl .products-table td .td-title {
                float: right;
                margin-right: 0;
                margin-left: 5px;
            }
        }

        .profit-wrapper {
            text-align: left;
            margin: 10px 0;
        }

        .profit-wrapper > div {
            display: inline-block;
            padding: 0 10px;
            line-height: 20px;
        }

        .profit-wrapper .profit {
            border-right: 1px solid #000;
        }

        .signature-table td {
            vertical-align: top;
        }

        .signature-table .shop-signature .title,
        .signature-table .signature-customer .title {
            display: block;
            margin-bottom: 10px;
        }

        img.watermark {
            position: absolute;
            top: 50%;
            right: 50%;
            transform: translate(50%, -50%);
            z-index: 999;
        }


        .condensed {
            line-height: 20px;
        }

        .condensed table th, .condensed table td {
            padding: 1px;
        }

        .condensed .profit-wrapper {
            margin-top: 0;
            margin-bottom: 0;
        }

        @media (max-width: 576px) {
            td.to-word {
                display: table-cell;
            }
        }

        .rtl {
            text-align: right;
            direction: rtl;
        }

        .rtl .container {
            text-align: right;
            direction: rtl;
        }

        .rtl .profit-wrapper .profit {
            border-left: 1px solid #000;
            border-right: none;
        }

        .invoice .view-1 .shop-info {
            border: 1px solid #000;
            text-align: right;
        }

        .invoice .view-1 .shop-info tfoot td {
            background: rgba(146, 146, 146, 0.25);
        }

        .invoice .view-1 .shop-info tfoot .component {
            display: inline-block;
        }

        @media (max-width: 576px) {
            .invoice .view-1 .shop-info tfoot, .invoice .view-1 .shop-info tfoot tr {
                display: block;
            }
        }

        .invoice .view-1 .customer-info {
            border: 1px solid #000;
            padding: 15px;
            margin: 20px 0;
        }

        .invoice .view-1 .customer-info .component {
            display: inline-block;
        }

        .invoice .view-1 th {
            background: rgba(146, 146, 146, 0.25);
        }

        .invoice .view-1 .products-table {
            text-align: center;
        }

        .invoice .view-1 .products-table .variation dt, .invoice .view-1 .products-table .variation dd {
            display: inline-block;
        }

        .invoice .view-1 .total-table {
            text-align: center;
        }

        @media (min-width: 576px) {
            .invoice .view-1 .total-table {
                width: 50%;
                margin-left: auto;
            }
        }

        .invoice .view-1 .customer-note, .invoice .view-1 .order-note {
            padding-left: 20px;
            margin: 20px 0;
            position: relative;
        }

        .invoice .view-1 .customer-note, .invoice .view-1 .order-note:before {
            position: absolute;
            width: 12px;
            height: 12px;
            left: 0;
            content: '';
            background: #000;
            top: 12px;
        }

        .invoice.rtl .view-1 .customer-note, .invoice.rtl .view-1 .order-note {
            border-left: none;
            padding-left: 0;
            padding-right: 15px;
            position: relative;
        }

        .invoice.rtl .view-1 .customer-note, .invoice.rtl .view-1 .order-note:before {
            position: absolute;
            width: 12px;
            height: 12px;
            right: 0;
            content: '';
            background: #000;
            top: 12px;
        }

        .invoice.condensed .view-1 .customer-info {
            margin-top: 5px;
            margin-bottom: 5px;
            padding-top: 5px;
            padding-bottom: 5px;
        }

        .invoice.condensed .view-1 .customer-note, .invoice.condensed .view-1 .order-note {
            margin-top: 5px;
            margin-bottom: 5px;
        }

        .component.print-date, .component.order-id, .component.order-status, .barcode {
            text-align: left;
        }

        .component.title, .component.url, .component.email {
            text-align: right;
        }

        .invoice .view-1 .customer-info {
            text-align: center;
        }

        @media (max-width: 576px) {
            .component.print-date, .component.order-id, .component.order-status, .barcode {
                text-align: center;
            }

            .component.title, .component.url, .component.email {
                text-align: center;
            }

            .invoice .view-1 .shop-info {
                text-align: center;
            }


        }

        .woocommerce-Price-currencySymbol {
            font-size: 12px;
        }


        *, body, select, input {
            font-family: 'IRANSans', Tahoma, Arial, sans-serif !important;
        }

        .container {
            border-color: #000;
        }

        .container {
            margin: 20px;
        }

    </style>
</head>
<body class="invoice rtl">
<div class="view-1 container">
    <table class="shop-info table-responsive table-fixed">
        <tbody>
        <tr>
            <td>
                <div class="component title">
                    <span class="content">
                        <span class="title">فروشنده:</span>
                        <span class="inner-content">{{ option('factor_title') ?? option('info_site_title') }}</span>
                    </span>
                </div>
                <div class="component url">
                    <span class="content">
                        <span class="title">وب‌سایت:</span>
                        <span class="inner-content">{{ url('/') }}</span>
                    </span>
                </div>
                @if(option('info_email'))
                    <div class="component email">
                        <span class="content">
                            <span class="title">ایمیل:</span>
                            <span class="inner-content">{{ option('info_email') }}</span>
                        </span>
                    </div>
                @endif
                @if(option('info_tel'))
                    <div class="component phone">
                        <span class="content">
                            <span class="title">تلفن:</span>
                            <span class="inner-content">{{ option('info_tel') }}</span>
                        </span>
                    </div>
                @endif
            </td>
            <td>
                <div class="shop-logo">
                    <img src="{{ option('info_logo', theme_asset('public/img/logo.png')) }}" alt="{{ option('info_site_title') }}" />
                </div>
            </td>
            <td>
                <div class="component print-date">
                    <span class="content">
                        <span class="title">تاریخ:</span>
                        <span class="inner-content">{{ tverta($order->created_at)->format('Y-m-d') }}</span>
                    </span>
                </div>
                <div class="component order-id">
                    <span class="content">
                        <span class="title">شناسه سفارش:</span>
                        <span class="inner-content">{{ $order->id }}</span>
                    </span>
                </div>
                @if (option('factor_registeration_id'))
                    <div class="component order-id">
                        <span class="content">
                            <span class="title">شماره ثبت:</span>
                            <span class="inner-content">{{ option('factor_registeration_id') }}</span>
                        </span>
                    </div>
                @endif
                @if (option('factor_economical_number'))
                    <div class="component order-id">
                        <span class="content">
                            <span class="title">شناسه اقتصادی:</span>
                            <span class="inner-content">{{ option('factor_economical_number') }}</span>
                        </span>
                    </div>
                @endif
                @if (option('factor_national_code'))
                    <div class="component order-id">
                        <span class="content">
                            <span class="title">شناسه ملی:</span>
                            <span class="inner-content">{{ option('factor_national_code') }}</span>
                        </span>
                    </div>
                @endif
            </td>
        </tr>
        </tbody>
        <tfoot>
            @if(option('info_address'))
                <tr>
                    <td colspan="3">
                        <div class="component address">
                            <span class="content">
                                <span class="title">آدرس:</span>
                                <span class="inner-content">{{ option('info_address') }}</span>
                            </span>
                        </div>
                    </td>
                </tr>
            @endif
        </tfoot>
    </table>
    <div class="customer-info">
        <div class="component recipient">
            <span class="content">
                <span class="title">گیرنده:</span>
                <span class="inner-content">
                    ایران،
                    استان
                    {{ $order->province ? $order->province->name : '' }}
                    ، ‌شهر
                    {{ $order->city ? $order->city->name : '' }}
                    ،
                    {{ $order->address }}
                </span>
            </span>
        </div>
        <div class="component full-name">
            <span class="content">
                <span class="title">خریدار:</span>
                <span class="inner-content">{{ $order->name }}</span>
            </span>
        </div>
        <div class="component postcode">
            <span class="content">
                <span class="title">کدپستی:</span>
                <span class="inner-content">{{ $order->postal_code }}</span>
            </span>
        </div>
        <div class="component phone">
            <span class="content">
                <span class="title">تلفن:</span>
                <span class="inner-content">{{ $order->mobile }}</span>
            </span>
        </div>
    </div>
    <table class="products-table table-responsive table-bordered">
        <thead>
            <tr>
                <th style="width: 5%">ردیف</th>
                <th style="width: 7%">کد کالا</th>
                <th style="width: 40%">شرح کالا</th>
                <th style="width: 5%">تعداد</th>
                <th style="width: 12.66%">مبلغ واحد</th>
                <th style="width: 12.66%">مبلغ کل</th>
            </tr>
        </thead>
        <tbody>
        @foreach($order->items as $item)
            <tr class="odd">
                <td class="row"><span>{{ $loop->iteration }}</span></td>
                <td class="id"><span class="td-title">کد کالا</span>@if($item->product){{ $item->product->sku }}@else -- @endif</td>
                <td class="product">
                    <span class="td-title">شرح کالا</span>
                    <a href="{{ route('front.products.show', ['product' => $item->product]) }}" target="_blank">
                        {{ $item->title }}
                        @if ($item->get_price)
                            <ul class="product-attributes">
                                @foreach ($item->get_price->get_attributes as $attribute)
                                    @if ($attribute->group->type == 'color')
                                        <li class="order-product-color d-print-none" style="background-color: {{ $attribute->value }};"></li>
                                        <li>{{ $attribute->group->name }}: {{ $attribute->name }}</li>
                                    @else
                                        <li>{{ $attribute->group->name }}: {{ $attribute->name }}</li>
                                    @endif
                                @endforeach
                                <li>انبار: {{ $item->product->warehouse->name }}</li>
                            </ul>
                        @endif
                    </a>
                </td>
                <td class="quantity"><span class="td-title">تعداد</span>{{ $item->quantity }}</td>
                <td class="price">
                    <span class="td-title">قیمت</span>
                    @if($item->realPrice() > $item->price)
                        <del>
                            <span class="woocommerce-Price-amount amount">
                                <bdi>{{ number_format($item->realPrice()) }}
                                    <span class="woocommerce-Price-currencySymbol">تومان</span>
                                </bdi>
                            </span>
                        </del>
                    @endif
                    <ins>
                        <span class="woocommerce-Price-amount amount">
                            <bdi>{{ number_format($item->price) }}
                                <span class="woocommerce-Price-currencySymbol">تومان</span>
                            </bdi>
                        </span>
                    </ins>
                </td>
                <td class="total-amount">
                    <span class="td-title">مبلغ کل</span>
                    <span class="woocommerce-Price-amount amount">
                        <bdi>{{ number_format($item->price * $item->quantity) }}
                            <span class="woocommerce-Price-currencySymbol">تومان</span>
                        </bdi>
                    </span>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="profit-wrapper"></div>
    <table class="total-table table-responsive table-bordered table-fixed">
        <tbody>
            <tr>
                <th class="total">مبلغ کل</th>
                <td class="total">
                    <span class="woocommerce-Price-amount amount">
                        <bdi>{{ number_format($order->price - $order->shipping_cost) }}
                            <span class="woocommerce-Price-currencySymbol">تومان</span>
                        </bdi>
                    </span>
                </td>
            </tr>
            <tr>
                <th class="shipping">مبلغ حمل و نقل</th>
                <td class="shipping">
                    <span class="woocommerce-Price-amount amount">
                        <bdi>
                            @if($order->shipping_cost == 0) رایگان
                            @else {{ number_format($order->shipping_cost) }} <span class="woocommerce-Price-currencySymbol">تومان</span>
                            @endif
                        </bdi>
                    </span>
                </td>
            </tr>
            <tr>
                <th class="final">مبلغ نهایی</th>
                <td class="final">
                    <span class="woocommerce-Price-amount amount">
                        <bdi>{{ number_format($order->price) }}
                            <span class="woocommerce-Price-currencySymbol">تومان</span>
                        </bdi>
                    </span>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div class="print">
    <a href="#" onclick="print()" class="button">
        <img src="{{ asset('public/print.png') }}" alt="print" />
    </a>
</div>

<p style="page-break-before: always;"></p>

</body>
</html>
@endforeach
