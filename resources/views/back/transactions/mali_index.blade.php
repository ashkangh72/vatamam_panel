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
                                    <li class="breadcrumb-item">مدیریت کاربران
                                    </li>
                                    <li class="breadcrumb-item active">اطلاعات مالی کاربران
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">

                @include('back.users.partials.filters')

                <section id="main-card" class="card">
                    <div class="card-header">
                        <h4 class="card-title">لیست کاربران</h4>
                        <h4 class="card-title"></h4>
                        <h4 class="card-title"></h4>

                        <button class="btn btn-outline-primary">مجموع کیف پول کل کاربران:‌
                            {{ convertPrice($balance) }}</button>

                        <button class="btn btn-outline-primary">مجموع صندوق امانات کل کاربران:‌
                            {{ convertPrice($box) }}</button>
                        <h4 class="card-title"></h4>
                        <h4 class="card-title"></h4>
                        <h4 class="card-title"></h4>

                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="datatable datatable-bordered datatable-head-custom" id="users_datatable"
                                data-action="{{ route('admin.users.apiIndex') }}"></div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>
@endsection

@include('back.partials.plugins', ['plugins' => ['datatable']])

@php
    $help_videos = [config('general.video-helpes.users')];
@endphp


@push('scripts')
    <script src="{{ asset('public/back/assets/js/pages/users/index_mali.js') }}"></script>
@endpush
