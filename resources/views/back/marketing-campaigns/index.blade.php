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
                                    <li class="breadcrumb-item">مدیریت کمپین های بازاریابی</li>
                                    <li class="breadcrumb-item active">لیست کمپین ها</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">

                @if($marketingCampaigns->count())
                    <section class="card">
                        <div class="card-header">
                            <h4 class="card-title">لیست کمپین ها</h4>
                            @can('users.marketing-campaigns.create')
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li><a class="btn btn-outline-primary waves-effect waves-light pr-1" href="{{ route('admin.users.marketing-campaigns.create') }}"><i class="fa fa-plus"></i> ایجاد کمپین </a></li>
                                    </ul>
                                </div>
                            @endcan
                        </div>
                        <div class="card-content" id="main-card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-center">نام</th>
                                                <th class="text-center">شروع و پایان</th>
                                                <th class="text-center">عملیات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($marketingCampaigns as $marketingCampaign)
                                                <tr id="marketingCampaign-{{ $marketingCampaign->id }}-tr">
                                                    <td class="text-center">{{ $marketingCampaign->name }}</td>
                                                    <td class="text-center">
                                                        {{ jdate($marketingCampaign->start_at)->format('%d %B %Y H:i:s') }}
                                                        <br>
                                                        {{ jdate($marketingCampaign->end_at)->format('%d %B %Y H:i:s') }}
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group" role="group" aria-label="عملیات">
                                                            @can('users.marketing-campaigns.update')
                                                                <a href="{{ route('admin.users.marketing-campaigns.edit', ['marketingCampaign' => $marketingCampaign]) }}" class="btn btn-success waves-effect waves-light">ویرایش</a>
                                                            @endcan

                                                            @can('users.marketing-campaigns.tariffs')
                                                                <button type="button" class="btn btn-info waves-effect waves-light" data-toggle="modal" data-target="#tariffs-modal-{{ $marketingCampaign->id }}">تعرفه ها</button>
                                                            @endcan
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
                            <h4 class="card-title">لیست کمپین ها</h4>
                            @can('users.marketing-campaigns.create')
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li><a class="btn btn-outline-primary waves-effect waves-light pr-1" href="{{ route('admin.users.marketing-campaigns.create') }}"><i class="fa fa-plus"></i> ایجاد کمپین </a></li>
                                    </ul>
                                </div>
                            @endcan
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
                {{ $marketingCampaigns->links() }}

            </div>
        </div>
    </div>

    {{-- delete page modal --}}
    @foreach ($marketingCampaigns as $marketingCampaign)
        <div class="modal fade text-left" id="tariffs-modal-{{ $marketingCampaign->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel19" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="myModalLabel19">تعرفه های کمپین {{ $marketingCampaign->name }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if($marketingCampaign->tariffs()->count())
                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead>
                                    <tr>
                                        <th class="text-center">حداقل خرید</th>
                                        <th class="text-center">کمیسیون ها</th>
                                        <th class="text-center">تاریخ ایجاد</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($marketingCampaign->tariffs as $tariffs)
                                        <tr id="marketingCampaign-{{ $tariffs->id }}-tr">
                                            <td class="text-center">{{ number_format($tariffs->minimum_purchase) }}</td>
                                            <td class="text-center">
                                                <span class="badge badge-secondary" data-toggle="tooltip" data-placement="right" title="کمیسیون محصولات">{{ $tariffs->products_commission_percent . '%' }}</span>
                                                <span class="badge badge-secondary" data-toggle="tooltip" data-placement="top" title="کمیسیون محصولات تخفیفی">{{ $tariffs->discounted_products_commission_percent . '%' }}</span>
                                                <span class="badge badge-secondary" data-toggle="tooltip" data-placement="left" title="کمیسیون سفارشات تخفیفی">{{ $tariffs->discounted_orders_commission_percent . '%' }}</span>
                                            </td>
                                            <td class="text-center">
                                                {{ jdate($tariffs->created_at)->format('%d %B %Y H:i:s') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center">
                                تعرفه ای ایجاد نشده!
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>
    @endforeach

@endsection
