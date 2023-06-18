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
                                    <li class="breadcrumb-item">تنظیمات
                                    </li>
                                    <li class="breadcrumb-item active">تنظیمات دیگر
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- users edit start -->
                <section class="users-edit">
                    <div class="card">
                        <div id="main-card" class="card-content">
                            <div class="card-body">
                                <div class="tab-content">
                                    <form id="others-form" action="{{ route('admin.settings.others') }}" method="POST">
                                        <h3 class="mt-2">تنظیمات pusher</h3>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>PUSHER_APP_ID</label>
                                                <div class="input-group mb-75">
                                                    <input type="text" name="PUSHER_APP_ID" class="form-control ltr" value="{{ config('broadcasting.connections.pusher.app_id') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label>PUSHER_APP_KEY</label>
                                                <div class="input-group mb-75">
                                                    <input type="text" name="PUSHER_APP_KEY" class="form-control ltr" value="{{ config('broadcasting.connections.pusher.key') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label>PUSHER_APP_SECRET</label>
                                                <div class="input-group mb-75">
                                                    <input type="text" name="PUSHER_APP_SECRET" class="form-control ltr" value="{{ config('broadcasting.connections.pusher.secret') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label>PUSHER_APP_CLUSTER</label>
                                                <div class="input-group mb-75">
                                                    <input type="text" name="PUSHER_APP_CLUSTER" class="form-control ltr" value="{{ config('broadcasting.connections.pusher.options.cluster') }}">
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-12 d-flex flex-sm-row flex-column justify-content-end mt-1">
                                                <button type="submit" class="btn btn-primary glow">ذخیره تغییرات</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- users edit ends -->

            </div>
        </div>
    </div>

@endsection

@include('back.partials.plugins', ['plugins' => ['jquery.validate']])

@push('scripts')
    <script src="{{ asset('back/assets/js/pages/settings/others.js') }}"></script>
@endpush
