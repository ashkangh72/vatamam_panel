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
                                    <li class="breadcrumb-item active">لیست تراکنش ها
                                    </li>
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
                                <form id="filter-transactions-form" method="GET">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>نام و نام خانوادگی</label>
                                            <fieldset class="form-group">
                                                <input type="text" class="form-control datatable-filter" name="fullname"
                                                    value="{{ request('fullname') }}">
                                            </fieldset>
                                        </div>

                                        <div class="col-md-2">
                                            <label>نام کاربری</label>
                                            <fieldset class="form-group">
                                                <input class="form-control datatable-filter" name="username"
                                                    value="{{ request('username') }}">
                                            </fieldset>
                                        </div>

                                        <div class="col-md-2">
                                            <label>شماره کاربر</label>
                                            <fieldset class="form-group">
                                                <input type="text" class="form-control datatable-filter" name="phone"
                                                    value="{{ request('phone') }}">
                                            </fieldset>
                                        </div>
                                        <div class="col-md-2">
                                            <label>آیدی کاربر</label>
                                            <fieldset class="form-group">
                                                <input type="text" class="form-control datatable-filter" name="id"
                                                    value="{{ request('id') }}">
                                            </fieldset>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <section id="main-card" class="card">
                    <div class="card-header">
                        <h4 class="card-title">لیست تراکنش ها</h4>
                        <h4 class="card-title"></h4>
                        <h4 class="card-title"></h4>
                        <div>
                            @foreach ($user->bankAccounts as $item)
                                <button class="btn btn-outline-primary">{{ $item->bank_name }}: {{ $item->iban }}</button> </br>
                            @endforeach
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="mb-2 collapse datatable-actions">
                                <div class="d-flex align-items-center">
                                    <div class="font-weight-bold text-danger mr-3"><span
                                            id="datatable-selected-rows">0</span> مورد انتخاب شده: </div>

                                    <button class="btn btn-danger mr-2" type="button" data-toggle="modal"
                                        data-target="#multiple-delete-modal">حذف همه</button>
                                </div>
                            </div>
                            <div class="datatable datatable-bordered datatable-head-custom" id="transactions_datatable"
                                data-action="{{ route('admin.mali.detail.apiIndex') }}"></div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>

    <!-- show Modal -->
    <div class="modal fade text-left" id="show-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel21">جزئیات تراکنش</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="transaction-detail" class="modal-body">


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@include('back.partials.plugins', ['plugins' => ['datatable']])

@push('scripts')
    <script src="{{ asset('public/back/assets/js/pages/transactions/user_index.js') }}"></script>
@endpush
