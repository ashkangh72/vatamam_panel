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
                                    <li class="breadcrumb-item active">بازدیدها(نموداری)</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-body">
                <section class="card" id="statistics-card">
                    <div class="card-content">
                        <div class="card-body">
                            <ul class="nav nav-tabs mb-2" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-toggle="tab" href="#view-counts" role="tab" aria-controls="view-counts" aria-selected="true">
                                        تعداد بازدیدها
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" data-toggle="tab" href="#viewer-counts" role="tab" aria-controls="viewer-counts" aria-selected="true">
                                        تعداد بازدیدگنندگان
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="view-counts" role="tabpanel" aria-labelledby="value">
                                    @include('back.statistics.views.filter-tabs')

                                    <div id="view-counts-chart" class="chart-area" style="min-height: 445px;" data-min-height="445px" data-action="{{ route('admin.statistics.viewCounts') }}"></div>

                                    <div class="col-12 mt-2">
                                        <div class="row">
                                            <div class="col-md-3 mb-2">
                                                <span class="border-bottom">کل بازدیدها : <span class="views-total"></span></span>
                                            </div>
                                            <div class="col-md-3 mb-2">
                                                <span class="border-bottom">میانگین : <span class="views-avg"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="viewer-counts" role="tabpanel" aria-labelledby="value">
                                    @include('back.statistics.views.filter-tabs')

                                    <div id="viewer-counts-chart" class="chart-area" style="min-height: 445px;" data-min-height="445px" data-action="{{ route('admin.statistics.viewerCounts') }}"></div>

                                    <div class="col-12 mt-2">
                                        <div class="row">
                                            <div class="col-md-3 mb-2">
                                                <span class="border-bottom">کل بازدیدها : <span class="viewers-total"></span></span>
                                            </div>
                                            <div class="col-md-3 mb-2">
                                                <span class="border-bottom">میانگین : <span class="viewers-avg"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="col-12">
                    <section class="card">
                        <div class="card-header">
                            <h4 class="card-title">بازدید: بر اساس کاربران</h4>
                            <div class="card-header-action">
                                <span type="button" class="btn btn-outline-dark" id="lastSevenDaysViewsChart"
                                      onclick="viewsChart(null,'last_seven_days')">هفته اخیر</span>
                                <span type="button" class="btn btn-outline-dark" id="lastThirtyDaysViewsChart"
                                      onclick="viewsChart(null,'last_thirty_Days')">ماه اخیر</span>
                                <span type="button" class="btn btn-outline-dark" id="lastMonthViewsChart"
                                      onclick="viewsChart(null,'last_month')">ماه گذشته</span>
                            </div>
                        </div>
                        <div class="card-content" id="main-card">
                            <div class="card-body">
                                <h6 id="firstCardH6" class="text-center">در حال بارگزاری...</h6>
                                <div id="viewsByDayChart" style="width: 100%" class="chartsh chart-shadow"></div>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">بازدید: بر اساس صفحه</h4>
                                <div class="card-header-action">
                                    <span type="button" class="btn btn-outline-dark" id="lastSevenDaysViewsTable"
                                          onclick="routeViewsTable(null,'last_seven_days')">هفته اخیر</span>
                                    <span type="button" class="btn btn-outline-dark" id="lastThirtyDaysViewsTable"
                                          onclick="routeViewsTable(null,'last_thirty_Days')">ماه اخیر</span>
                                    <span type="button" class="btn btn-outline-dark" id="lastMonthViewsTable"
                                          onclick="routeViewsTable(null,'last_month')">ماه گذشته</span>
                                </div>
                            </div>
                            <div class="card-content" id="main-card">
                                <div class="card-body">
                                    <div class="table-responsive text-center" id="viewsByRouteTableDiv">
                                        <h6 id="secondCardH6" class="text-center">در حال بارگزاری...</h6>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>سیستم عامل کاربران</h4>
                            </div>
                            <div class="card-body">
                                <h6 id="thirdCardH6" class="text-center">در حال بارگزاری...</h6>
                                <div id="viewersPlatformsPie" class="chartsh"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('back.partials.plugins', ['plugins' => ['apexcharts', 'persian-datepicker']])

@push('scripts')
    <script src="{{ asset('back/app-assets/vendors/js/charts/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('back/assets/js/pages/statistics/views.js') }}{{--?v=1--}}"></script>
@endpush
