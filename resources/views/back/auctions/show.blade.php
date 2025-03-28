@extends('back.layouts.master')

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('public/back/assets/css/pages/users/show.css') }}">
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
                            <h2 class="content-header-title float-left mb-0">مشخصات</h2>
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">مدیریت
                                    </li>
                                    <li class="breadcrumb-item">مدیریت مزایدها و محصولات
                                    </li>
                                    <li class="breadcrumb-item active">مشخصات
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="content-body">
                @php
                    $title = \App\Enums\AuctionConditionEnum::getTitle($auction->condition);
                    $city = \App\Models\City::find($auction->city_id);
                @endphp
                <section class="card">
                    <div class="card-header">
                        <h4 class="card-title">مشخصات {{ $auction->title }}</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <section class="page-users-view">
                                <div class="row">
                                    <!-- account start -->
                                    <div class="col-12">
                                        <div class="card mb-0">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 col-sm-9 col-md-6 col-lg-5">
                                                        <table>
                                                            <tr>
                                                                <td class="font-weight-bold">عنوان</td>
                                                                <td>{{ $auction->title }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="font-weight-bold">نوع</td>
                                                                <td>{{ $auction->type == 'auction' ? 'مزایده' : 'محصول' }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="font-weight-bold">دسته بندی</td>
                                                                <td>{{ $auction->category->title }}</td>
                                                                {{-- <td>{{ $auction-> ?: '--' }}</td> --}}
                                                            </tr>
                                                            <tr>
                                                                <td class="font-weight-bold">وضعیت کالا</td>
                                                                <td>{{ $title }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="font-weight-bold">استان</td>
                                                                <td>{{ $city->province->name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="font-weight-bold">شهرستان</td>
                                                                <td>{{ $city->name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="font-weight-bold">تاریخ ایجاد</td>
                                                                <td>
                                                                    <abbr
                                                                        title="{{ tverta($auction->created_at) }}">{{ tverta($auction->created_at) }}</abbr>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="font-weight-bold">ویدئو</td>
                                                                <td>
                                                                    <a href="{{ $auction->video }}" target="_blank">
                                                                        <video class="table-img" src="{{ $auction->video }}">
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="font-weight-bold">قیمت پایه</td>
                                                                <td>{{ number_format($auction->base_price) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="font-weight-bold">ضریب افزایش</td>
                                                                <td>{{ number_format($auction->increase_step_price) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="font-weight-bold">حداقل قیمت فروش</td>
                                                                <td>{{ number_format($auction->minimun_sale_price) }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="font-weight-bold">قیمت فروش سریع</td>
                                                                <td>{{ number_format($auction->quick_sale_price) }}</td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <div class="col-12 col-md-12 col-lg-5">
                                                        <table class="ml-0 ml-sm-0 ml-lg-0">
                                                            <tr>
                                                                <td class="font-weight-bold">نام کاربر</td>
                                                                <td>{{ $auction->user->name }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="font-weight-bold">وضعیت</td>
                                                                <td>
                                                                    @if ($auction->status->name === 'rejected')
                                                                        <span class="badge badge-danger">رد
                                                                            شده</span> <small>دلیل:
                                                                            {{ $auction->reject_reason }}</small>
                                                                    @elseif ($auction->status->name === 'approved')
                                                                        <span class="badge badge-success">تایید
                                                                            شده</span>
                                                                    @elseif ($auction->status->name === 'pending_approval')
                                                                        <span class="badge badge-info">منتظر
                                                                            تایید</span>
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="font-weight-bold">شناسه مزایده/محصول</td>
                                                                <td>{{ $auction->sku }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="font-weight-bold">تضمینی است؟</td>
                                                                <td>
                                                                    @if ($auction->guaranteed)
                                                                        بله
                                                                    @else
                                                                        خیر
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="font-weight-bold">مبلغ تضمین</td>
                                                                @if ($auction->guaranteed)
                                                                    <td>{{ number_format($auction->guaranteePrice()) }}</td>
                                                                @else
                                                                    --
                                                                @endif
                                                            </tr>
                                                            <tr>
                                                                <td class="font-weight-bold">توضیحات</td>
                                                                <td>{{ $auction->description }}</td>
                                                            </tr>
                                                            @if ($auction->status->name != 'pending_approval')
                                                            <tr>
                                                                <td class="font-weight-bold">مشاهده محصول در وب سایت</td>
                                                                <td><a href="{{ $auction->getUrl() }}" target="blank">
                                                                        مشاهده </a></td>
                                                            </tr>
                                                            @endif
                                                            <tr>
                                                                <td class="font-weight-bold">تصویر اصلی</td>
                                                                <td>
                                                                    <a href="{{ $auction->picture }}" target="_blank">
                                                                        <img class="table-img"
                                                                            src="{{ $auction->picture }}">
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="font-weight-bold">سایر تصاویر</td>
                                                                <td>
                                                                    @foreach ($auction->pictures as $item)
                                                                        <a href="{{ $item->path }}" target="_blank">
                                                                            <img class="table-img"
                                                                                src="{{ $item->path }}">
                                                                        </a>
                                                                    @endforeach
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- account end -->

                                </div>
                            </section>
                            <!-- page users view end -->

                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    {{-- delete user modal --}}
    <div class="modal fade text-left" id="user-delete-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel19"
        style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel19">آیا مطمئن هستید؟</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    با حذف کاربر دیگر قادر به بازیابی آن نخواهید بود
                </div>
                <div class="modal-footer">
                    <form action="#" id="user-delete-form">
                        @csrf
                        @method('delete')
                        <button type="button" class="btn btn-success waves-effect waves-light"
                            data-dismiss="modal">خیر</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light">بله حذف شود</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- delete post modal --}}
    <div class="modal fade text-left" id="delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel19">آیا مطمئن هستید؟</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    با حذف درخواست دیگر قادر به بازیابی آن نخواهید بود
                </div>
                <div class="modal-footer">
                    <form action="#" id="agency-delete-form">
                        @csrf
                        @method('delete')
                        <button type="button" class="btn btn-success waves-effect waves-light"
                            data-dismiss="modal">خیر</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light">بله حذف شود</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('public/back/assets/js/pages/users/show.js') }}"></script>
@endpush
