@extends('back.layouts.master')

@push('styles')
    <style>
        .chart-shadow {
            -webkit-filter: drop-shadow(0px 9px 2px rgba(0, 0, 0, 0.3));
            filter: drop-shadow(0px 9px 2px rgba(0, 0, 0, 0.3))
        }

        .chartsh {
            height: 16rem
        }
    </style>
@endpush

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
                                    <li class="breadcrumb-item">گزارشات</li>
                                    <li class="breadcrumb-item active">فروشگاه</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <div class="card">
                    <div class="card-header filter-card">
                        <h4 class="card-title">فیلتر کردن</h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="feather icon-chevron-down"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse {{ request()->except('page') ? 'show' : '' }}">
                        <div class="card-body">
                            <div class="users-list-filter">
                                <form id="filter_results"
                                      onsubmit="filterResults()"
                                      class="form-horizontal" role="form">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>از تاریخ: </label>
                                            <fieldset class="form-group">
                                                <input autocomplete="off" type="text" class="form-control" id="total_sales_start_date_picker"
                                                       placeholder="از تاریخ ...">
                                                <input type="hidden" name="start_date" id="total_sales_start_date" required>
                                            </fieldset>
                                        </div>

                                        <div class="col-md-3">
                                            <label>تا تاریخ: </label>
                                            <fieldset class="form-group">
                                                <input autocomplete="off" type="text" class="form-control" id="total_sales_end_date_picker"
                                                       placeholder="تا تاریخ ...">
                                                <input type="hidden" name="end_date" id="total_sales_end_date" required>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <button type="submit" class="btn btn-outline-primary col-sm-2 offset-sm-9">فیلتر نتایج</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h6 id="forthCardH6" class="text-center">در حال بارگزاری...</h6>
                        <div id="totalSalesChart" class="chartsh chart-shadow"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>درآمد فروشگاه</h4>
                            </div>
                            <div class="card-body">
                                <ul class="nav nav-pills" id="sales" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="total_sales_tab" data-toggle="tab" href="#total_sales" role="tab" aria-controls="total_sales" aria-selected="true">فروش کل</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="products_sales_tab" data-toggle="tab" href="#products_sales" role="tab" aria-controls="products_sales" aria-selected="false">محصولات</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="myTabContent2">
                                    <div class="tab-pane fade show active" id="total_sales" role="tabpanel" aria-labelledby="total_sales_tab">
                                        <div class="table-responsive text-center" id="totalSalesTableDiv">
                                            <h6 id="secondCardH6" class="text-center">در حال بارگزاری...</h6>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="products_sales" role="tabpanel" aria-labelledby="products_sales_tab">
                                        <div class="table-responsive text-center" id="productsSalesTableDiv">
                                            <h6 id="thirdCardH6" class="text-center">در حال بارگزاری...</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@include('back.partials.plugins', ['plugins' => ['jquery-tagsinput', 'jquery.validate', 'jquery-ui', 'jquery-ui-sortable', 'persian-datepicker']])

@push('scripts')
    <script src="{{ asset('public/back/app-assets/vendors/js/charts/echarts/echarts.min.js') }}"></script>
    <script>
        let totalSalesByDayChart;

        function sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }

        function totalSalesTable(startDate, endDate) {
            let date = startDate ? ('?start_datetime='+startDate + "&end_datetime=" + endDate) : '';
            let url = BASE_URL+"/statistics/e-commerce/total-sales"+date;

            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    let totalSalesTableDiv = $("#totalSalesTableDiv");
                    let rows = `
                    <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>تاریخ</th>
                            <th>فروش(تومان)</th>
                            <th>فروش خالص(تومان)</th>
                            <th>حمل و نقل(تومان)</th>
                        </tr>
                    </thead>
                    <tbody>`;
                    for (var i = 0; i < data.orders.length; i++) {
                        rows += `
                        <tr>
                            <td>${data.orders[i].date}</td>
                            <td>${data.orders[i].total_sale}</td>
                            <td>${data.orders[i].gross_sale}</td>
                            <td>${data.orders[i].total_shipping_cost}</td>
                        </tr>`;
                    }

                    rows += `</tbody></table>`;

                    $('#secondCardH6').remove()
                    totalSalesTableDiv.empty().append(rows)
                },
                beforeSend: function (xhr) {
                    block('#main-card');
                    xhr.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                },
                complete: function () {
                    unblock('#main-card');
                },
                cache: false,
                contentType: false,
                processData: false
            });
        }

        function productsSalesTable(startDate, endDate) {
            let date = startDate ? ('?start_datetime='+startDate + "&end_datetime=" + endDate) : ''
            let url = BASE_URL+"/statistics/e-commerce/products-sales"+date;

            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    let productsSalesTableDiv = $("#productsSalesTableDiv");
                    let rows = `
                    <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>محصول</th>
                            <th>فروش(تعداد)</th>
                            <th>مبلغ(تومان)</th>
                        </tr>
                    </thead>
                    <tbody>`;
                    for (var i = 0; i < data.order_items.length; i++) {
                        rows += `
                        <tr>
                            <td>${data.order_items[i].product_title}</td>
                            <td>${data.order_items[i].total_quantity}</td>
                            <td>${data.order_items[i].total_prices}</td>
                        </tr>`;
                    }

                    rows += `</tbody></table>`;

                    $('#thirdCardH6').remove()
                    productsSalesTableDiv.empty().append(rows)
                },
                beforeSend: function (xhr) {
                    block('#main-card');
                    xhr.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                },
                complete: function () {
                    unblock('#main-card');
                },
                cache: false,
                contentType: false,
                processData: false
            });
        }

        function filterResults() {
            event.preventDefault();

            let inputs = $('#filter_results').serializeArray()

            sleep(20).then(function () { totalSalesTable(inputs[0].value, inputs[1].value) })
            sleep(50).then(function () { productsSalesTable(inputs[0].value, inputs[1].value) })
            sleep(70).then(function () { totalSalesChart('update', inputs[0].value, inputs[1].value) })
        }

        function setTotalSalesChart(type, data) {
            var chart = document.getElementById('totalSalesChart');
            var labels = data.dates;
            var grossSale = data.gross_sale;
            var totalSale = data.total_sale;

            if (type === 'new') {
                totalSalesByDayChart = echarts.init(chart);
                $('#forthCardH6').remove()
            }

            totalSalesByDayChart.setOption({
                grid: {
                    top: '6',
                    right: '0',
                    bottom: '17',
                    left: '75',
                },
                xAxis: {
                    data: labels,
                    axisLine: {
                        lineStyle: {
                            color: '#576574'
                        }
                    },
                    axisLabel: {
                        fontSize: 10,
                        color: '#576574'
                    }
                },
                tooltip: {
                    show: true,
                    showContent: true,
                    alwaysShowContent: false,
                    triggerOn: 'mousemove',
                    trigger: 'axis',
                    axisPointer:
                        {
                            label: {
                                show: false,
                            },
                        }
                },
                yAxis: {
                    splitLine: {
                        lineStyle: {
                            color: 'none'
                        }
                    },
                    axisLine: {
                        lineStyle: {
                            color: '#576574'
                        }
                    },
                    axisLabel: {
                        fontSize: 10,
                        color: '#576574'
                    }
                },
                series: [
                    {
                        name: 'فروش کل',
                        type: 'line',
                        smooth: true,
                        symbolSize: 10,
                        size: 10,
                        data: totalSale,
                        color: ['#00d2d3']
                    },
                    {
                        name: 'فروش خالص',
                        type: 'line',
                        smooth: true,
                        symbolSize: 10,
                        size: 10,
                        data: grossSale,
                        color: ['#ff9f43']
                    },
                ],
            });
        }
        function totalSalesChart(type, startDate, endDate) {
            let date = startDate ? ('?start_datetime='+startDate + "&end_datetime=" + endDate) : ''
            let url = BASE_URL+"/statistics/e-commerce/total-sales-chart"+date;
            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    setTotalSalesChart(type, data);
                },
                beforeSend: function (xhr) {
                    block('#main-card');
                    xhr.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                },
                complete: function () {
                    unblock('#main-card');
                },
                cache: false,
                contentType: false,
                processData: false
            });
        }

        $(document).ready(function(){
            $("#total_sales_start_date_picker").pDatepicker({
                timePicker: {
                    enabled: true,
                    meridian: {
                        enabled: false,
                    },
                    second: {
                        enabled: false,
                    }
                },
                toolbox: {
                    // enabled: true,
                    calendarSwitch: {
                        enabled: false,
                    },
                },
                initialValue: false,
                altField: '#total_sales_start_date',
                altFormat: 'YYYY-MM-DD H:mm:ss',

                onSelect: function (unixDate) {
                    var date = $('#total_sales_start_date').val();
                    $('#total_sales_start_date').val(date.toEnglishDigit());
                }
            });
            $("#total_sales_end_date_picker").pDatepicker({
                timePicker: {
                    enabled: true,
                    meridian: {
                        enabled: false,
                    },
                    second: {
                        enabled: false,
                    }
                },
                toolbox: {
                    // enabled: true,
                    calendarSwitch: {
                        enabled: false,
                    },
                },
                initialValue: false,
                altField: '#total_sales_end_date',
                altFormat: 'YYYY-MM-DD H:mm:ss',

                onSelect: function (unixDate) {
                    var date = $('#total_sales_end_date').val();
                    $('#total_sales_end_date').val(date.toEnglishDigit());
                }
            });

            /**
             * set default data's
             */
            sleep(20).then(function () { totalSalesTable() })
            sleep(50).then(function () { productsSalesTable() })
            sleep(70).then(function () { totalSalesChart('new') })
        });
    </script>
@endpush
