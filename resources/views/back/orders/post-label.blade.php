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
            max-width: 19.7cm;
            -webkit-print-color-adjust: exact;
        }

        body {
            padding: 0.5cm;
            height: 100vh;
            /*display: flex;*/
            align-items: center;
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
            font: 9pt iranyekan;
            direction: rtl;
        }

        .page {
            background: white;
            float: right;
            width: 100%;
        }

        .flex {
            display: flex;
        }

        .flex > * {
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

        .barcode-generate, .barcode-generate-followup {
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

        .br-bt {
            border-bottom: 1px solid #000;
            padding: 0.12cm !important;
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
    <table class="header-table" id="factor" style="width: 100%">
        <tr>
            <td style="padding: 0 4px 0 0;height: 2cm;">
                <div class="bordered grow header-item-data" style="border-left: 0;border-bottom: 0;">
                    <table class="grow centered">
                        <tr>
                            <td colspan="2">
                                <span class="label">فرستنده:</span> فروشگاه اینترنتی {{ option('info_site_title') }} {{ url('/') }}
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <span class="label">آدرس:</span> {{ option('info_address') }}
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <span class="label">صندوق پستی:</span> {{ option('info_postal_code') }}
                            </td>
                            <td>
                                <span class="label">تلفن:</span> {{ option('info_tel') }}
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
            <td style="width: 4.5cm;height: 2cm;">
                <div class="bordered grow" style="padding: 2mm 5mm;border-right: 0;border-bottom: 0;">
                    <div class="flex" style="align-items: center;height: 100%;">
                        <img src="{{ asset('public/logo.png') }}" alt="">
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td style="padding: 0 4px 0 0;height: 2cm;">
                <div class="bordered grow header-item-data" style="border-left: 0;padding: 0;">
                    <table class="grow centered">
                        <tr>
                            <td colspan="2" class="br-bt">
                                <span class="label">گیرنده:</span> {{ $order->name }}
                            </td>
                            <td colspan="2" class="br-bt">
                                <span class="label">تلفن:</span> {{ $order->mobile }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="br-bt">
                                <span class="label">نشانی:</span>
                                استان
                                {{ $order->province ? $order->province->name : '' }}
                                ، ‌شهر
                                {{ $order->city ? $order->city->name : '' }}
                                ،
                                {{ $order->address }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="br-bt">
                                <span class="label">کد فاکتور:</span> {{ 'SLF-'. $order->id }}
                            </td>
                            <td colspan="2" class="br-bt">
                                <span class="label">کدپستی:</span> {{ $order->postal_code }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"
                                style="font-size: 18px;font-family:Arial, Helvetica, sans-serif;font-weight:bold;padding:0.12cm;">
                                <span class="label"><img src="data:image/svg+xml;base64,
                                        PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjEiIGlkPSJDYXBhXzEiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNDcyLjE0OCA0NzIuMTQ4IiBzdHlsZT0iZW5hYmxlLWJhY2tncm91bmQ6bmV3IDAgMCA0NzIuMTQ4IDQ3Mi4xNDg7IiB4bWw6c3BhY2U9InByZXNlcnZlIiB3aWR0aD0iNTEyIiBoZWlnaHQ9IjUxMiIgY2xhc3M9IiI+PGc+PGc+Cgk8Zz4KCQk8Zz4KCQkJPHBhdGggZD0iTTQwNy42OTgsMjA5LjA2OFY3NS44NDNjMC0yMi45ODgtMjAuMzc1LTM5LjE4NC00My4zNjMtMzkuMTg0aC0zNS4wMDRWOC40NDhjMC42MjYtMy45OTEtMi4xMDItNy43MzMtNi4wOTMtOC4zNTkgICAgIGMtMC43NTEtMC4xMTgtMS41MTYtMC4xMTgtMi4yNjYsMGgtNDkuNjMzYy01Ljc0NywwLTkuOTI3LDIuNjEyLTkuOTI3LDguMzU5VjM2LjY2SDE0Ni40NzRWOC40NDggICAgIGMwLTUuNzQ3LTYuNzkyLTguMzU5LTEyLjUzOS04LjM1OUg4NC4zMDJjLTUuNzQ3LDAtMTAuOTcxLDIuNjEyLTEwLjk3MSw4LjM1OVYzNi42Nkg0MS40NjIgICAgIGMtMjIuOTg4LDAtNDEuMjczLDE2LjE5Ni00MS4yNzMsMzkuMTg0djMwMy4wMmMwLDIyLjk4OCwxOC4yODYsMzkuMTg0LDQxLjI3MywzOS4xODRIMjE1Ljk2ICAgICBjNDkuNDczLDYyLjAzNywxMzkuODcsNzIuMjIzLDIwMS45MDcsMjIuNzVjMzMuOTc1LTI3LjA5NCw1My44NjItNjguMTExLDU0LjA5My0xMTEuNTY2ICAgICBDNDcxLjExMywyODEuMTcyLDQ0Ny4xOTksMjM2LjQ1NSw0MDcuNjk4LDIwOS4wNjh6IE0yODIuMzExLDIwLjk4NmgyNi4xMjJ2NDcuMDJoLTI2LjEyMlYyMC45ODZ6IE05NC4yMjksMjAuOTg2aDMxLjM0N3Y0Ny4wMiAgICAgSDk0LjIyOVYyMC45ODZ6IE0yMS4wODYsNzUuODQzYzAtMTEuNDk0LDguODgyLTE4LjI4NiwyMC4zNzYtMTguMjg2aDMxLjg2OXYxOS44NTNjMC4yNDEsNi4wNDgsNC45NDIsMTAuOTcyLDEwLjk3MSwxMS40OTQgICAgIGg0OS42MzNjNi4zNTgtMC40MTYsMTEuNTcyLTUuMTk2LDEyLjUzOS0xMS40OTRWNTcuNTU4aDExNC45Mzl2MTkuODUzYy0wLjMwNiw1Ljg3Miw0LjA3MiwxMC45NDIsOS45MjcsMTEuNDk0aDQ5LjYzMyAgICAgYzUuNzQ3LDAsOC4zNTktNS43NDcsOC4zNTktMTEuNDk0VjU3LjU1OGgzNS4wMDRjMTEuNDk0LDAsMjIuNDY1LDYuNzkyLDIyLjQ2NSwxOC4yODZ2NjUuMzA2SDIxLjA4NlY3NS44NDN6IE00MS40NjIsMzk3LjE1ICAgICBjLTExLjQ5NCwwLTIwLjM3Ni02Ljc5Mi0yMC4zNzYtMTguMjg2VjE2Mi4wNDdIMzg2Ljh2MzYuMDQ5Yy0xOC4zNTYtNy44MzQtMzguMDM5LTEyLjA4OS01Ny45OTItMTIuNTM5ICAgICBjLTc5LjM0OSwwLjA5Ni0xNDMuNTk2LDY0LjQ5OC0xNDMuNSwxNDMuODQ3YzAuMDI5LDIzLjY0Miw1Ljg5MSw0Ni45MTEsMTcuMDY3LDY3Ljc0NUg0MS40NjJ6IE0zMjcuNzY0LDQ1MS40ODQgICAgIGMtNjcuODA2LDAuMjg4LTEyMy4wMDgtNTQuNDQ3LTEyMy4yOTYtMTIyLjI1M2MtMC4yODgtNjcuODA2LDU0LjQ0Ny0xMjMuMDA4LDEyMi4yNTMtMTIzLjI5NiAgICAgYzY3LjgwNi0wLjI4OCwxMjMuMDA4LDU0LjQ0NywxMjMuMjk2LDEyMi4yNTNjMC4wMDEsMC4zNDgsMC4wMDEsMC42OTUsMCwxLjA0M0M0NTAuMDE3LDM5Ni43NSwzOTUuMjgyLDQ1MS40ODQsMzI3Ljc2NCw0NTEuNDg0eiAgICAgIiBkYXRhLW9yaWdpbmFsPSIjMDAwMDAwIiBjbGFzcz0iYWN0aXZlLXBhdGgiIHN0eWxlPSJmaWxsOiMwMDAwMDAiPjwvcGF0aD4KCQkJPHBhdGggZD0iTTM3Ni44NzQsMzI5LjIzMWgtNDcuNTQzdi02Mi42OTRjMC01Ljc3MS00LjY3OC0xMC40NDktMTAuNDQ5LTEwLjQ0OWMtNS43NzEsMC0xMC40NDksNC42NzgtMTAuNDQ5LDEwLjQ0OXY3My4xNDMgICAgIGMxLjAzLDYuMDk5LDYuMzU0LDEwLjUzNSwxMi41MzksMTAuNDQ5aDU1LjkwMmM1Ljc3MSwwLDEwLjQ0OS00LjY3OCwxMC40NDktMTAuNDQ5UzM4Mi42NDUsMzI5LjIzMSwzNzYuODc0LDMyOS4yMzF6IiBkYXRhLW9yaWdpbmFsPSIjMDAwMDAwIiBjbGFzcz0iYWN0aXZlLXBhdGgiIHN0eWxlPSJmaWxsOiMwMDAwMDAiPjwvcGF0aD4KCQk8L2c+Cgk8L2c+CjwvZz48L2c+IDwvc3ZnPg=="
                                                         width="16" style="float:right;margin-top:2px"/></span>
                                {{ tverta($order->created_at)->format('Y-m-d') }}
                            </td>
                            <td colspan="2" style="padding:0.12cm;">
                                <span class="label"></span>پرداخت:
                                @if ($order->gateway == 'wallet') کیف پول
                                @elseif ($order->gateway == 'cash') نقدی
                                @else {{ $order->gatewayRelation->name }}
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
            <td style="width: 4.5cm;height: 2cm;padding:0;">
                <div class="bordered grow" style="padding: 2mm 5mm;">
                    <div class="barcode">
                        <span class="barcode-generate-order">{{ 'SLF-'.$order->id }}</span>
                        <input type="hidden" id="codeOrder" value="{{ $order->id }}">
                    </div>
                    <div class="flex" style="align-items: center;margin-top: 2rem;">
                        <img src="{{ asset('public/logo.png') }}" alt="">
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-right: 4px;">
                <div class="bordered grow"
                     style="padding-top: 0;border-top: 0;display: flow-root;">
                        <span class="label" style="margin-left: 5px;float:right">شماره بسته: <br>
                            <span style="font-family:Arial, Helvetica, sans-serif;">{{ 'SLB-'.$order->id }}</span>
                            <br>
                            <span style="font-family:Arial, Helvetica, sans-serif;">{{ todayVerta()->format('Y-m-d') }}</span>
                        </span>
                    <div class="barcode" style="margin-top: 13px;float:left;">
                        <span class="barcode-generate">{{ 'SLB-'.$order->id }}</span>
                        <input type="hidden" id="codePackage" value="{{ $order->id }}">
                    </div>
                </div>
            </td>
        </tr>
    </table>
    <a class="btn-printPage">چاپ برچسپ</a>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
        integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('public/back/assets/js/jquery-barcode.min.js') }}"></script>
<script>
    $(document).ready(function () {
        var valuecodeFactor = $("#codePackage").val();
        var btypecodeFactor = "code128";
        var settingscodeFactor = {
            barWidth: 1,
            barHeight: 25,
            showHRI: false,
        };
        $(".barcode-generate").barcode(
            valuecodeFactor, btypecodeFactor, settingscodeFactor
        );

        var valueFollowUp = $("#codeOrder").val();
        var btypeFollowUp = "codabar";
        var settingsFollowUp = {
            barWidth: 1,
            barHeight: 25,
            showHRI: false,
        };
        $(".barcode-generate-order").barcode(
            valueFollowUp, btypeFollowUp, settingsFollowUp
        );
    });
    $('a.btn-printPage').click(function () {
        window.print();
        return false;
    });
</script>
</body>
