@extends('back.layouts.master')

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('public/back/app-assets/plugins/jquery-ui/jquery-ui.css') }}">
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
                                    <li class="breadcrumb-item">مدیریت تعرفه های کمیسیون
                                    </li>
                                    <li class="breadcrumb-item active">ویرایش تعرفه
                                    </li>
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
                        <h4 class="card-title">ویرایش تعرفه</h4>
                    </div>

                    <div id="main-card" class="card-content">
                        <div class="card-body">
                            <div class="col-12 col-md-10 offset-md-1">
                                <form class="form" id="commission-tariff-edit-form" action="{{ route('admin.commission_tariffs.update', ['commission_tariff' => $commissionTariff]) }}" method="post">
                                    @csrf
                                    @method('put')
                                    <div class="form-body">
                                        <div class="row">

                                            <div class="col-md-4 col-6">
                                                <div class="form-group">
                                                    <label>حداقل</label>
                                                    <input type="number" class="form-control min-input" name="min" value="{{ $commissionTariff->min }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-6">
                                                <div class="form-group">
                                                    <label>حداکثر</label>
                                                    <input type="number" class="form-control max-input" name="max" value="{{ $commissionTariff->max }}">
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-6">
                                                <div class="form-group">
                                                    <label>درصد کمیسیون</label>
                                                    <input type="number" class="form-control commission-percent-input" name="commission_percent" min="0" max="100" value="{{ $commissionTariff->commission_percent }}">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 text-right">
                                                    <button type="submit" class="btn btn-primary mr-1 mb-1 waves-effect waves-light">ویرایش تعرفه</button>
                                                </div>
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

@push('scripts')
    <script src="{{ asset('public/back/app-assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('public/back/app-assets/plugins/jquery-validation/localization/messages_fa.min.js') }}"></script>
    <script src="{{ asset('public/back/app-assets/plugins/jquery-ui/jquery-ui.js') }}"></script>

    <script src="{{ asset('public/back/assets/js/pages/commission-tariffs/edit.js') }}"></script>
@endpush
