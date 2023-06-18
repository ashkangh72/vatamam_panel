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
                                    <li class="breadcrumb-item active">لیست درخواست های بازگردانی</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">

                @if($refundRequests->count())
                    <section class="card">
                        <div class="card-header">
                            <h4 class="card-title">لیست درخواست های بازگردانی</h4>
                        </div>
                        <div class="card-content" id="main-card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>سفارش</th>
                                                <th>محصول</th>
                                                <th>کاربر</th>
                                                <th>دلیل</th>
                                                <th>تاریخ</th>
                                                <th>وضعیت</th>
                                                <th class="text-center">عملیات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($refundRequests as $refundRequest)
                                                <tr id="refund-request-{{ $refundRequest->id }}-tr">
                                                    <td class="text-center">{{ $refundRequest->id }}</td>
                                                    <td>
                                                        <a href="{{ Route::has('front.orders.show') ? route('front.orders.show', ['order' => $refundRequest->orderItem->order]) : '' }}"
                                                           target="_blank"><i class="feather icon-external-link"></i>
                                                        </a>
                                                        {{ $refundRequest->orderItem->order_id }}
                                                    </td>
                                                    <td>
                                                        {{ $refundRequest->orderItem->product->title }}
                                                        <a href="{{ Route::has('front.products.show') ? route('front.products.show', ['product' => $refundRequest->orderItem->product]) : '' }}"
                                                           target="_blank"><i class="feather icon-external-link"></i>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="{{ Route::has('admin.users.show') ? route('admin.users.show', ['user' => $refundRequest->orderItem->order->user]) : '' }}"
                                                           target="_blank"><i class="feather icon-external-link"></i>
                                                        </a>
                                                        {{ $refundRequest->orderItem->order->user->fullname ?? '--' }}
                                                    </td>
                                                    <td>
                                                        @switch($refundRequest->reason)
                                                            @case('stricken')
                                                                زدگی یا آسیب در زمان تحویل
                                                                @break
                                                            @case('change_color_size')
                                                                درخواست تعویض رنگ یا اندازه
                                                                @break
                                                            @case('wrong_color_size')
                                                                رنگ یا اندازه اشتباه دریافت شده
                                                                @break
                                                        @endswitch
                                                    </td>
                                                    <td>{{ jdate($refundRequest->created_at)->format('%d %B %Y H:i:s') }}</td>
                                                    <td>
                                                        @switch($refundRequest->status)
                                                            @case('waiting')
                                                            <span class="text-info">در حال بررسی</span>
                                                            @break
                                                            @case('waiting_to_receive')
                                                            <span class="text-warning">درانتظار دریافت محصول</span>
                                                            @break
                                                            @case('received')
                                                            <span class="text-success">محصول دریافت شده</span>
                                                            @break
                                                            @case('rejected')
                                                            <span class="text-danger">رد شده</span>
                                                            @break
                                                        @endswitch
                                                    </td>

                                                    <td class="text-center">
                                                        <button type="button" data-refund-request="{{ $refundRequest->id }}" class="btn btn-success mr-1 waves-effect waves-light show-refund-request">مشاهده</button>
                                                        <button data-refund-request="{{ $refundRequest->id }}" data-id="{{ $refundRequest->id }}" type="button" class="btn btn-danger mr-1 waves-effect waves-light btn-delete" data-toggle="modal" data-target="#delete-modal">حذف</button>
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
                            <h4 class="card-title">لیست درخواست های بازگردانی</h4>
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

                {{ $refundRequests->links() }}

            </div>
        </div>
    </div>

    {{-- delete modal --}}
    <div class="modal fade text-left" id="delete-modal" tabindex="-1" role="dialog"  aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel19">آیا مطمئن هستید؟</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    با حذف درخواست بازگردانی دیگر قادر به بازیابی آن نخواهید بود
                </div>
                <div class="modal-footer">
                    <form action="#" id="refund-request-delete-form">
                        @csrf
                        @method('delete')
                        <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal">خیر</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light">بله حذف شود</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- show Modal -->
    <div class="modal fade text-left" id="show-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel21">جزئیات درخواست بازگردانی</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div id="refund-request-detail" class="modal-body"></div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('back/assets/js/pages/refund-requests/index.js') }}"></script>
@endpush
