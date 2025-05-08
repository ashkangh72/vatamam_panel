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
                                    <li class="breadcrumb-item active">لیست کاربران بلاک شده
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
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="datatable datatable-bordered datatable-head-custom" id="users_datatable"
                                data-action="{{ route('admin.users_block.apiIndex') }}"></div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>

    {{-- delete modal --}}
    <div class="modal fade text-left" id="delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel19">آیا مطمئن هستید؟</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <form action="#" id="unblock-form" method="post">
                        @csrf
                        <button type="button" class="btn btn-success waves-effect waves-light"
                            data-dismiss="modal">خیر</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light">بله حذف شود</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('back.partials.plugins', ['plugins' => ['datatable']])

@php
    $help_videos = [config('general.video-helpes.users')];
@endphp


@push('scripts')
    <script src="{{ asset('public/back/assets/js/pages/users/index_block.js') }}"></script>
@endpush
