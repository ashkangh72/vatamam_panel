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
                                    <li class="breadcrumb-item active">لیست برداشت ها</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                @if ($walletCheckouts->count())
                    <section class="card">
                        <div class="card-header">
                            <h4 class="card-title">لیست برداشت ها</h4>
                        </div>
                        <div class="card-content" id="main-card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>کاربر</th>
                                                <th>کد ملی</th>
                                                <th>موجودی (تومان)</th>
                                                <th>مبلغ (تومان)</th>
                                                <th>تاریخ</th>
                                                <th>وضعیت</th>
                                                <th class="text-center">عملیات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($walletCheckouts as $walletCheckout)
                                                <tr id="wallet-checkout-{{ $walletCheckout->id }}-tr">
                                                    <td class="text-center">{{ $walletCheckout->id }}</td>
                                                    <td>
                                                        <a href="{{ Route::has('admin.users.show') ? route('admin.users.show', ['user' => $walletCheckout->user]) : '' }}"
                                                            target="_blank"><i class="feather icon-external-link"></i>
                                                        </a>
                                                        {{ $walletCheckout->user->name ?? '--' }}
                                                    </td>
                                                    <td>{{ $walletCheckout->user->national_id ?? '--' }}</td>
                                                    <td>{{ number_format($walletCheckout->user->wallet->balance) }}</td>
                                                    <td>{{ number_format($walletCheckout->amount) }}</td>
                                                    <td>{{ jdate($walletCheckout->created_at)->format('%d %B %Y H:i:s') }}
                                                    </td>
                                                    <td>
                                                        @if ($walletCheckout->status == \App\Enums\WalletCheckoutStatusEnum::pending_approval)
                                                            <div class="badge badge-pill badge-info badge-md">در انتظار
                                                                بررسی</div>
                                                        @elseif($walletCheckout->status == \App\Enums\WalletCheckoutStatusEnum::rejected)
                                                            <div class="badge badge-pill badge-danger badge-md">رد شده</div>
                                                        @elseif($walletCheckout->walletCheckoutTransaction && $walletCheckout->walletCheckoutTransaction->status == 'TRANSFERRED')
                                                            <div class="badge badge-pill badge-success badge-md">انتقال وجه با موفقیت انجام شد</div>
                                                        @elseif($walletCheckout->walletCheckoutTransaction && $walletCheckout->walletCheckoutTransaction->status == 'FAILED')
                                                            <div class="badge badge-pill badge-danger badge-md">انتقال وجه ناموفق بود</div>
                                                        @elseif($walletCheckout->walletCheckoutTransaction && $walletCheckout->walletCheckoutTransaction->status == 'TRANSFERRED_REVERTED')
                                                            <div class="badge badge-pill badge-success badge-md">انتقال وجه برگشت داده شد</div>
                                                        @elseif($walletCheckout->walletCheckoutTransaction && $walletCheckout->walletCheckoutTransaction->status == 'FAILED_WRONG')
                                                            <div class="badge badge-pill badge-danger badge-md">انتقال وجه ناموفق بود</div>
                                                        @else
                                                            <div class="badge badge-pill badge-warning badge-md">در حال
                                                                انجام تراکنش برداشت</div>
                                                        @endif
                                                    </td>

                                                    <td class="text-center">
                                                        <div class="btn-group-vertical">
                                                            @if ($walletCheckout->status != \App\Enums\WalletCheckoutStatusEnum::approved)
                                                                <a data-id="{{ $walletCheckout->id }}" id="accept-btn"
                                                                    href="#"
                                                                    class="btn btn-outline-success waves-effect waves-light">تایید</a>

                                                                <a data-id="{{ $walletCheckout->id }}" id="reject-btn"
                                                                    href="#"
                                                                    class="btn btn-outline-danger waves-effect waves-light">رد</a>
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

                    {{ $walletCheckouts->links() }}
                @else
                    <section class="card">
                        <div class="card-header">
                            <h4 class="card-title">لیست برداشت ها</h4>
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

    <div class="modal fade text-left" id="checkout-accept-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel19">آیا مطمئن هستید؟</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" data-id="">

                </div>
                <div class="modal-footer">
                    <form action="{{ route('admin.wallets.checkouts.accept') }}" id="checkout-accept-form">
                        @csrf
                        <button type="button" class="btn btn-danger waves-effect waves-light"
                            data-dismiss="modal">خیر</button>
                        <button type="submit" class="btn btn-success waves-effect waves-light">بله تایید شود</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade text-left" id="checkout-reject-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel19">آیا مطمئن هستید؟</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" data-id="">

                </div>
                <div class="modal-footer">
                    <form action="{{ route('admin.wallets.checkouts.reject') }}" id="checkout-reject-form">
                        @csrf
                        <button type="button" class="btn btn-success waves-effect waves-light"
                            data-dismiss="modal">خیر</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light">بله رد شود</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('public/back/assets/js/pages/wallets/checkouts.js') }}"></script>
@endpush
