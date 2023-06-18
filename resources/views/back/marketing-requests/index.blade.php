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
                                    <li class="breadcrumb-item active">لیست درخواست های بازاریابی</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">

                @if($marketingRequests->count())
                    <section class="card">
                        <div class="card-header">
                            <h4 class="card-title">لیست درخواست های بازاریابی</h4>
                        </div>
                        <div class="card-content" id="main-card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>کاربر</th>
                                                <th>شهر</th>
                                                <th>پیشبینی فروش</th>
                                                <th>روش فروش</th>
                                                <th>تاریخ</th>
                                                <th>وضعیت</th>
                                                <th class="text-center">عملیات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($marketingRequests as $marketingRequest)
                                                <tr id="marketing-request-{{ $marketingRequest->id }}-tr">
                                                    <td class="text-center">{{ $marketingRequest->id }}</td>
                                                    <td>
                                                        <a href="{{ Route::has('admin.users.show') ? route('admin.users.show', ['user' => $marketingRequest->user]) : '' }}"
                                                           target="_blank"><i class="feather icon-external-link"></i>
                                                        </a>
                                                        {{ $marketingRequest->full_name ?: $marketingRequest->user->fullname }}
                                                    </td>
                                                    <td>{{ $marketingRequest->city ? $marketingRequest->city->name : '--' }}</td>
                                                    <td>{{ $marketingRequest->getMonthlySalesForecast()? $marketingRequest->getMonthlySalesForecast()['text'] : '--' }}</td>
                                                    <td>
                                                        @foreach($marketingRequest->getSaleMethod() as $saleMethod)
                                                            <span class="badge badge-pill badge-secondary badge-sm">{{ $saleMethod['text'] }}</span>
                                                        @endforeach
                                                    </td>
                                                    <td>{{ jdate($marketingRequest->created_at)->format('%d %B %Y H:i:s') }}</td>
                                                    <td>
                                                        <span class="{{ $marketingRequest->getStatus() ? $marketingRequest->getStatus()['class'] : '' }}">{{ $marketingRequest->getStatus() ? $marketingRequest->getStatus()['text'] : '' }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group-vertical mt-2 mb-2" role="group" aria-label="عملیات">
                                                            @switch($marketingRequest->status)
                                                                @case('waiting')
                                                                    <button data-marketing-request="{{ $marketingRequest->id }}" data-id="{{ $marketingRequest->id }}" type="button" class="btn btn-success mr-1 waves-effect waves-light btn-accept" data-toggle="modal" data-target="#accept-modal">تایید</button>
                                                                    <button data-marketing-request="{{ $marketingRequest->id }}" data-id="{{ $marketingRequest->id }}" type="button" class="btn btn-danger mr-1 waves-effect waves-light btn-reject" data-toggle="modal" data-target="#reject-modal">رد</button>
                                                                    @break
                                                                @case('accepted')
                                                                    <button data-marketing-request="{{ $marketingRequest->id }}" data-id="{{ $marketingRequest->id }}" type="button" class="btn btn-danger mr-1 waves-effect waves-light btn-reject" data-toggle="modal" data-target="#reject-modal">رد</button>
                                                                    @break
                                                                @case('rejected')
                                                                    <button data-marketing-request="{{ $marketingRequest->id }}" data-id="{{ $marketingRequest->id }}" type="button" class="btn btn-success mr-1 waves-effect waves-light btn-accept" data-toggle="modal" data-target="#accept-modal">تایید</button>
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
                            <h4 class="card-title">لیست درخواست های بازاریابی</h4>
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

                {{ $marketingRequests->links() }}

            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('back/assets/js/pages/marketing-requests/index.js') }}"></script>
@endpush
