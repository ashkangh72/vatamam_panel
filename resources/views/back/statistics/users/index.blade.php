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
                                    <li class="breadcrumb-item">مدیریت
                                    </li>
                                    <li class="breadcrumb-item">گزارشات
                                    </li>
                                    <li class="breadcrumb-item active">کاربران
                                    </li>
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
                                    <a class="nav-link" data-toggle="tab" href="#user-counts" role="tab" aria-controls="user-counts" aria-selected="true">
                                      تعداد کاربران
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="user-counts" role="tabpanel" aria-labelledby="value">
                                    @include('back.statistics.users.filter-tabs')

                                    <div id="user-counts-chart" class="chart-area" style="min-height: 445px;" data-min-height="445px" data-action="{{ route('admin.statistics.userCounts') }}"></div>

                                    <div class="col-12 mt-2">
                                        <div class="row">
                                            <div class="col-md-3 mb-2">
                                                <span class="border-bottom">کل کاربران : <span class="users-total"></span></span>
                                            </div>
                                            <div class="col-md-3 mb-2">
                                                <span class="border-bottom">میانگین : <span class="users-avg"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                {{--<section class="section">
                    <div class="section-body">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">کاربران بر اساس تعداد خرید</h4>
                                <div class="card-header-action">
                                    <a type="button" class="btn btn-outline-dark waves-effect waves-light"
                                        href="{{ route('admin.statistics.userPurchaseCounts.export') }}">خروجی اکسل</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <h6 id="userPurchaseCountsChartLoader" class="text-center">در حال بارگزاری...</h6>
                                <div id="userPurchaseCountsChart" class="chartsh chart-shadow" data-action="{{ route('admin.statistics.userPurchaseCounts') }}"></div>
                            </div>
                        </div>
                    </div>
                </section>--}}
            </div>
        </div>
    </div>

@endsection

@include('back.partials.plugins', ['plugins' => ['apexcharts', 'persian-datepicker']])

@push('scripts')
    <script src="{{ asset('public/back/app-assets/vendors/js/charts/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('public/back/assets/js/pages/statistics/users.js') }}?v=1.1"></script>
@endpush
