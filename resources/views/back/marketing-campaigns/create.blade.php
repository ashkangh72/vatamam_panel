@extends('back.layouts.master')

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('back/app-assets/plugins/jquery-ui/jquery-ui.css') }}">
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
                                <li class="breadcrumb-item">مدیریت کمپین های بازاریابی</li>
                                <li class="breadcrumb-item active">ایجاد کمپین</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="content-body">
            <!-- Description -->
            <section class="card">
                <div class="card-header">
                    <h4 class="card-title">ایجاد کمپین جدید</h4>
                </div>

                <div id="main-card" class="card-content">
                    <div class="card-body">
                        <div class="col-12 col-md-10 offset-md-1">
                            <form class="form" id="marketing-campaign-create-form" action="{{ route('admin.users.marketing-campaigns.store') }}"
                                  method="post" data-redirect="{{ route('admin.users.marketing-campaigns.index') }}">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label>نام</label>
                                                <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label>تاریخ شروع</label>
                                                <input autocomplete="off" type="text" class="form-control" id="start_at_picker">
                                                <input type="hidden" name="start_at" id="start_at">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label>تاریخ پایان</label>
                                                <input autocomplete="off" type="text" class="form-control" id="end_at_picker">
                                                <input type="hidden" name="end_at" id="end_at">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div id="campaign-tariffs-area"></div>

                                        <div class="row">
                                            <div class="col-12 text-center mt-2">
                                                <button id="add-campaign-tariff" type="button" class="btn btn-outline-primary waves-effect waves-light"><i class="feather icon-plus"></i>افزودن تعرفه</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary btn-block mr-1 mb-1 waves-effect waves-light">ایجاد کمپین</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
            <!--/ Description -->
        </div>
    </div>
</div>

@endsection

@include('back.marketing-campaigns.partials.tariffs-template')
@include('back.partials.plugins', ['plugins' => ['jquery.validate', 'jquery-ui', 'persian-datepicker', 'jquery-ui-sortable']])

@push('scripts')
    <script>
        let tariffsCount = 0;
    </script>
    <script src="{{ asset('back/assets/js/pages/marketingCampaigns/all.js') }}?v=1.1"></script>
    <script src="{{ asset('back/assets/js/pages/marketingCampaigns/create.js') }}?v=1.1"></script>
@endpush
