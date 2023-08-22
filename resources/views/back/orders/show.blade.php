@extends('back.layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset('public/back/assets/css/pages/order.css') }}">
@endpush

@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-7 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb no-border">
                                    <li class="breadcrumb-item">مدیریت</li>
                                    <li class="breadcrumb-item">مدیریت سفارشات</li>
                                    <li class="breadcrumb-item active">اطلاعات سفارش شماره {{ $order->id }}</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="content-body">
                <!-- page users view start -->
                <section class="page-users-view">
                    <div class="row">

                        <div class="col-12 d-print-none">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">عملیات</div>
                                </div>
                                <div class="card-body" id="main-card">
                                    <div class="row">

                                        <div class="col-md-4">
                                            <span>{{ ($order->status != \App\Enums\OrderStatusEnum::paid) ? 'سفارش پرداخت نشده است' : '' }}</span>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="float-right">
                                                <a type="button"
                                                   href="{{ route('admin.orders.factor', ['order' => $order->id]) }}"
                                                   target="_blank"
                                                   class="btn btn-outline-primary mr-1 waves-effect waves-light">
                                                    <i class="feather icon-printer"></i>فاکتور
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">مشخصات کاربر</div>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li>
                                                <a href="{{ route('admin.users.show', ['user' => $order->user]) }}"
                                                   target="_blank">
                                                    <i class="feather icon-external-link"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <dt class="col-6">نام :</dt>
                                                        <dd class="col-6">{{ $order->user->name }}</dd>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <dt class="col-6">نام کاربری :</dt>
                                                        <dd class="col-6">{{ $order->user->username }}</dd>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <dt class="col-6">شماره تماس :</dt>
                                                        <dd class="col-6">{{ $order->user->phone ?: '-' }}</dd>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="row">
                                                        <dt class="col-6">ایمیل :</dt>
                                                        <dd class="col-6">{{ $order->user->email ?: '-' }}</dd>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- account end -->
                        <!-- information start -->
                        <div class="col-md-12 col-12 ">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">اطلاعات سفارش</div>
                                </div>
                                <div class="card-body row">
                                    <div class="col-md-6 col-12 ">
                                        <table class="details">
                                            <tbody>
                                            <tr>
                                                <td class="font-weight-bold">استان :</td>
                                                <td>{{ $order->address ? ($order->address->city ? $order->address->city->province->name : '--') : '--' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">شهر :</td>
                                                <td>{{ $order->address ? ($order->address->city ? $order->address->city->name : '--') : '--' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">کد پستی :</td>
                                                <td>{{ $order->address ? $order->address->postal_code : '--' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">آدرس کامل :</td>
                                                <td>{{ $order->address ? $order->address->address : '--' }}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-6 col-12 ">
                                        <table class="details">
                                            <tbody>
                                            <tr>
                                                <td class="font-weight-bold">تاریخ ثبت :</td>
                                                <td>{{ tverta($order->created_at) }}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">هزینه ارسال:</td>
                                                <td>{{ number_format($order->shipping_cost) }} تومان</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">تخفیف:</td>
                                                <td>{{ number_format($order->discount_amount) }} تومان</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">جمع قیمت</td>
                                                <td>{{ number_format($order->price) }} تومان</td>
                                            </tr>
                                            <tr>
                                                <td class="font-weight-bold">توضیحات سفارش :</td>
                                                <td>
                                                    {{ $order->description }}
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-lg-12 mt-3">
                                    <div class="table-responsive">
                                        <table class="table withdraw__table">
                                            <thead>
                                                <tr>
                                                    <th>شناسه(sku)</th>
                                                    <th>تصویر</th>
                                                    <th style="width: 300px;">نام مزایده</th>
                                                    <th>تعداد</th>
                                                    <th>قیمت واحد</th>
                                                    <th>قیمت کل</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($order->auctions as $auction)
                                                    <tr>
                                                        <td>{{ $auction->sku }}</td>
                                                        <td>
                                                            <a href="{{ env('WEBSITE_URL') . '/auction/' . $auction->slug }}" target="_blank">
                                                                <img class="table-img" src="{{ $auction->picture }}">
                                                            </a>
                                                        </td>
                                                        <td class="order-product-name">{{ $auction->title }}</td>
                                                        <td>{{ $auction->pivot->quantity }}</td>
                                                        <td>{{ number_format($auction->pivot->price) }} تومان</td>
                                                        <td>{{ number_format($auction->pivot->quantity * $auction->pivot->price) }} تومان</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- information start -->
                        <!-- social links end -->
                    </div>
                </section>
                <!-- page users view end -->
            </div>
        </div>
    </div>
@endsection
