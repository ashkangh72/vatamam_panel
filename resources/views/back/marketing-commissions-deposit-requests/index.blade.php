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
                                    <li class="breadcrumb-item active">لیست درخواست های برداشت کمیسیون کمپین بازاریابی</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">

                @if($marketingCampaignCommissionDepositRequests->count())
                    <section class="card">
                        <div class="card-header">
                            <h4 class="card-title">لیست درخواست های برداشت کمیسیون کمپین</h4>
                        </div>
                        <div class="card-content" id="main-card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>کاربر</th>
                                                <th class="text-center">کمیسیون</th>
                                                <th>تاریخ</th>
                                                <th>وضعیت</th>
                                                <th class="text-center">عملیات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($marketingCampaignCommissionDepositRequests as $marketingCampaignCommissionDepositRequest)
                                                <tr id="marketing-commission-deposit-request-{{ $marketingCampaignCommissionDepositRequest->id }}-tr">
                                                    <td class="text-center">{{ $marketingCampaignCommissionDepositRequest->id }}</td>
                                                    <td>
                                                        <a href="{{ Route::has('admin.users.show') ? route('admin.users.show', ['user' => $marketingCampaignCommissionDepositRequest->marketingCampaignCommission->user]) : '' }}"
                                                           target="_blank"><i class="feather icon-external-link"></i>
                                                        </a>
                                                        {{ $marketingCampaignCommissionDepositRequest->marketingCampaignCommission->user->fullname }}
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-lg badge-secondary" data-toggle="tooltip" data-placement="right" title="کمیسیون محصولات">
                                                            {{ number_format($marketingCampaignCommissionDepositRequest->marketingCampaignCommission->products_commission) }} تومان
                                                        </span>
                                                        <span class="badge badge-lg badge-secondary" data-toggle="tooltip" data-placement="top" title="کمیسیون محصولات تخفیفی">
                                                            {{ number_format($marketingCampaignCommissionDepositRequest->marketingCampaignCommission->discounted_products_commission) }} تومان
                                                        </span>
                                                        <span class="badge badge-lg badge-secondary" data-toggle="tooltip" data-placement="left" title="کمیسیون سفارشات تخفیفی">
                                                            {{ number_format($marketingCampaignCommissionDepositRequest->marketingCampaignCommission->discounted_orders_commission) }} تومان
                                                        </span>
                                                    </td>
                                                    <td>{{ jdate($marketingCampaignCommissionDepositRequest->created_at)->format('%d %B %Y H:i:s') }}</td>
                                                    <td>
                                                        <span class="{{ $marketingCampaignCommissionDepositRequest->getStatus() ? $marketingCampaignCommissionDepositRequest->getStatus()['class'] : '' }}">{{ $marketingCampaignCommissionDepositRequest->getStatus() ? $marketingCampaignCommissionDepositRequest->getStatus()['text'] : '' }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group-vertical mt-2 mb-2" role="group" aria-label="عملیات">
                                                            @switch($marketingCampaignCommissionDepositRequest->status)
                                                                @case('pending')
                                                                    <button data-id="{{ $marketingCampaignCommissionDepositRequest->id }}" type="button" class="btn btn-success mr-1 waves-effect waves-light btn-accept" data-toggle="modal" data-target="#accept-modal">تایید</button>
                                                                    <button data-id="{{ $marketingCampaignCommissionDepositRequest->id }}" type="button" class="btn btn-danger mr-1 waves-effect waves-light btn-reject" data-toggle="modal" data-target="#reject-modal">رد</button>
                                                                    @break
                                                                @case('accepted')
                                                                    <button data-id="{{ $marketingCampaignCommissionDepositRequest->id }}" type="button" class="btn btn-danger mr-1 waves-effect waves-light btn-reject" data-toggle="modal" data-target="#reject-modal">رد</button>
                                                                    @break
                                                                @case('rejected')
                                                                    <button data-id="{{ $marketingCampaignCommissionDepositRequest->id }}" type="button" class="btn btn-success mr-1 waves-effect waves-light btn-accept" data-toggle="modal" data-target="#accept-modal">تایید</button>
                                                                    @break
                                                            @endswitch
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

                @else
                    <section class="card">
                        <div class="card-header">
                            <h4 class="card-title">لیست درخواست های برداشت کمیسیون کمپین بازاریابی</h4>
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

                {{ $marketingCampaignCommissionDepositRequests->links() }}

            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('back/assets/js/pages/marketing-commissions-deposit-requests/index.js') }}{{--?v=1.0--}}"></script>
@endpush
