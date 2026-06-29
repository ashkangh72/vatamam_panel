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
                                    <li class="breadcrumb-item">مدیریت</li>
                                    <li class="breadcrumb-item active">برداشت کارشناسان</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                @if ($expertCheckouts->count())
                    <section class="card">
                        <div class="card-header">
                            <h4 class="card-title">لیست برداشت کارشناسان</h4>
                        </div>
                        <div class="card-content" id="main-card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>کاربر</th>
                                                <th>شماره شبا</th>
                                                <th>مبلغ (تومان)</th>
                                                <th>توضیحات</th>
                                                <th>تاریخ</th>
                                                <th>وضعیت</th>
                                                <th class="text-center">عملیات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($expertCheckouts as $expertCheckout)
                                                <tr id="expert-checkout-{{ $expertCheckout->id }}-tr">
                                                    <td class="text-center">{{ $expertCheckout->id }}</td>
                                                    <td>
                                                        <a href="{{ Route::has('admin.users.show') ? route('admin.users.show', ['user' => $expertCheckout->user]) : '' }}"
                                                            target="_blank"><i class="feather icon-external-link"></i>
                                                        </a>
                                                        {{ $expertCheckout->user->name ?? '--' }}
                                                    </td>
                                                    <td>{{ $expertCheckout->iban ?? '--' }}</td>
                                                    <td>{{ number_format($expertCheckout->amount) }}</td>
                                                    <td>{{ $expertCheckout->description ?? '--' }}</td>
                                                    <td>{{ jdate($expertCheckout->created_at)->format('%d %B %Y H:i:s') }}</td>
                                                    <td>
                                                        @if ($expertCheckout->status == \App\Enums\ExpertCheckoutStatusEnum::pending_approval)
                                                            <div class="badge badge-pill badge-info badge-md">در انتظار بررسی</div>
                                                        @elseif ($expertCheckout->status == \App\Enums\ExpertCheckoutStatusEnum::approved)
                                                            <div class="badge badge-pill badge-success badge-md">تایید شده</div>
                                                        @elseif ($expertCheckout->status == \App\Enums\ExpertCheckoutStatusEnum::rejected)
                                                            <div class="badge badge-pill badge-danger badge-md">رد شده</div>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group-vertical">
                                                            @if ($expertCheckout->status != \App\Enums\ExpertCheckoutStatusEnum::approved)
                                                                @can('transactions.expert_checkouts.accept')
                                                                    <a data-id="{{ $expertCheckout->id }}" id="accept-btn"
                                                                        href="#"
                                                                        class="btn btn-outline-success waves-effect waves-light">تایید</a>
                                                                @endcan
                                                            @endif
                                                            @if ($expertCheckout->status != \App\Enums\ExpertCheckoutStatusEnum::rejected)
                                                                @can('transactions.expert_checkouts.reject')
                                                                    <a data-id="{{ $expertCheckout->id }}" id="reject-btn"
                                                                        href="#"
                                                                        class="btn btn-outline-danger waves-effect waves-light">رد</a>
                                                                @endcan
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{ $expertCheckouts->links() }}
                @else
                    <section class="card">
                        <div class="card-header">
                            <h4 class="card-title">لیست برداشت کارشناسان</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="card-text">
                                    <p>چیزی برای نمایش وجود ندارد!</p>
                                </div>
                            </div>
                        </div>
                    </section>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="expert-checkout-accept-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">آیا مطمئن هستید؟</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" data-id=""></div>
                <div class="modal-footer">
                    <form action="{{ route('admin.expert_checkouts.accept') }}" id="expert-checkout-accept-form">
                        @csrf
                        <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal">خیر</button>
                        <button type="submit" class="btn btn-success waves-effect waves-light">بله تایید شود</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left" id="expert-checkout-reject-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">آیا مطمئن هستید؟</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" data-id=""></div>
                <div class="modal-footer">
                    <form action="{{ route('admin.expert_checkouts.reject') }}" id="expert-checkout-reject-form">
                        @csrf
                        <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal">خیر</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light">بله رد شود</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('public/back/assets/js/pages/expert_checkouts/index.js') }}"></script>
@endpush
