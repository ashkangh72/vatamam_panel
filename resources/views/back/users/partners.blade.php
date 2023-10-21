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
                                    <li class="breadcrumb-item active">لیست درخواست همکاری ها</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                @if($partners->count())
                    <section class="card">
                        <div class="card-header">
                            <h4 class="card-title">لیست درخواست همکاری ها</h4>
                        </div>
                        <div class="card-content" id="main-card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>کاربر</th>
                                                <th>نام فروشگاه</th>
                                                <th>آدرس فروشگاه</th>
                                                <th>اینستاگرام</th>
                                                <th>پیوست</th>
                                                <th>تاریخ</th>
                                                <th>وضعیت</th>
                                                <th class="text-center">عملیات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($partners as $partner)
                                                <tr id="partner-{{ $partner->id }}-tr">
                                                    <td class="text-center">{{ $partner->id }}</td>
                                                    <td>
                                                        <a href="{{ Route::has('admin.users.show') ? route('admin.users.show', ['user' => $partner->user]) : '' }}"
                                                           target="_blank"><i class="feather icon-external-link"></i>
                                                        </a>
                                                        {{ $partner->user->name ?? '--' }}
                                                    </td>
                                                    <td>{{ $partner->store_name }}</td>
                                                    <td>{{ $partner->store_address }}</td>
                                                    <td>
                                                        <a href="https://instagram.com/{{ $partner->instagram }}"><i class="feather icon-external-link"></i></a>
                                                        {{ $partner->instagram }}
                                                    </td>
                                                    <td>
                                                        @if($partner->document)
                                                            <a href="{{ $partner->document }}"
                                                               target="_blank"><i class="feather icon-external-link"></i></a>
                                                        @else
                                                            <span class="badge badge-pill badge-danger badge-md">ندارد</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ jdate($partner->created_at)->format('%d %B %Y H:i:s') }}</td>
                                                    <td>
                                                        @switch($partner->status)
                                                            @case(\App\Enums\PartnerStatusEnum::pending)
                                                                <div class="badge badge-pill badge-info badge-md">در انتظار بررسی</div>
                                                                @break
                                                            @case(\App\Enums\PartnerStatusEnum::accepted)
                                                                <div class="badge badge-pill badge-success badge-md">تایید شده</div>
                                                                @break
                                                            @case(\App\Enums\PartnerStatusEnum::rejected)
                                                                <div class="badge badge-pill badge-danger badge-md">رد شده</div>
                                                                @break
                                                        @endswitch
                                                    </td>

                                                    <td class="text-center">
                                                        <div class="btn-group-vertical">
                                                            @if($partner->status != \App\Enums\PartnerStatusEnum::accepted)
                                                                <a data-id="{{ $partner->id }}" id="accept-btn" href="#"
                                                                   class="btn btn-outline-success waves-effect waves-light">تایید</a>

                                                                <a data-id="{{ $partner->id }}" id="reject-btn" href="#"
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

                    {{ $partners->links() }}
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

    <div class="modal fade text-left" id="partner-accept-modal" tabindex="-1" role="dialog"  aria-hidden="true">
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
                    <form action="{{ route('admin.user.partners.accept') }}" id="partner-accept-form">
                        @csrf
                        <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal">خیر</button>
                        <button type="submit" class="btn btn-success waves-effect waves-light">بله تایید شود</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade text-left" id="partner-reject-modal" tabindex="-1" role="dialog"  aria-hidden="true">
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
                    <form action="{{ route('admin.user.partners.reject') }}" id="partner-reject-form">
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
    <script src="{{ asset('public/back/assets/js/pages/users/partners.js') }}"></script>
@endpush
