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
                                    <li class="breadcrumb-item active">مدیریت تیکت ها</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">

                <!-- filter start -->
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
                    <div class="card-content collapse show">
                        <div class="card-body pt-0">
                            <div class="users-list-filter">
                                <form id="filter-comments-form">
                                    <div class="row">
                                        <div class="col-12 col-sm-6 col-lg-3">
                                            <label for="filter-status">وضعیت</label>
                                            @php
                                                $new = \App\Enums\TicketStatusEnum::new->value;
                                                $admin_answer = \App\Enums\TicketStatusEnum::admin_answer->value;
                                                $user_answer = \App\Enums\TicketStatusEnum::user_answer->value;
                                                $closed = \App\Enums\TicketStatusEnum::closed->value;
                                            @endphp
                                            <fieldset class="form-group">
                                                <select class="form-control" name="status" id="filter-status">
                                                    <option value="">همه</option>
                                                    <option value="{{ $new }}"
                                                        {{ (int) request('status') == $new ? 'selected' : '' }}>
                                                        جدید
                                                    </option>
                                                    <option value="{{ $admin_answer }}"
                                                        {{ (int) request('status') == $admin_answer ? 'selected' : '' }}>
                                                        ادمین پاسخ داده
                                                    </option>
                                                    <option value="{{ $user_answer }}"
                                                        {{ (int) request('status') == $user_answer ? 'selected' : '' }}>
                                                        کاربر پاسخ داده
                                                    </option>
                                                    <option value="{{ $closed }}"
                                                        {{ (int) request('status') == $closed ? 'selected' : '' }}>
                                                        بسته شده
                                                    </option>
                                                </select>
                                            </fieldset>
                                        </div>
                                        <div class="col-12 col-sm-6 col-lg-3">
                                            <label for="filter-ordering">مرتب سازی</label>
                                            <fieldset class="form-group">
                                                <select class="form-control" name="ordering" id="filter-ordering">
                                                    <option value="latest"
                                                        {{ request('ordering') == 'latest' ? 'selected' : '' }}>
                                                        جدیدترین
                                                    </option>
                                                    <option value="oldest"
                                                        {{ request('ordering') == 'oldest' ? 'selected' : '' }}>
                                                        قدیمی ترین
                                                    </option>
                                                </select>
                                            </fieldset>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- filter end -->

                <div class="list-comments">
                    @if ($tickets->count())
                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">مدیریت تیکت ها</h4>
                            </div>
                            <div class="card-content" id="main-card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th>نام</th>
                                                    <th>عنوان</th>
                                                    <th class="text-center">وضعیت</th>
                                                    <th class="text-center">عملیات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($tickets as $ticket)
                                                    <tr id="comment-{{ $ticket->id }}-tr">
                                                        <td class="text-center">
                                                            {{ $ticket->id }}
                                                        </td>
                                                        <td style="white-space: nowrap">{{ $ticket->user->name ?: '---' }}
                                                        </td>
                                                        <td style="max-width: 300px">
                                                            {{ short_content($ticket->title, 20, false) }}</td>
                                                        <td class="text-center">
                                                            @if ($ticket->status->value == 1)
                                                                <div class="badge badge-pill badge-warning badge-md">جدید
                                                                </div>
                                                            @elseif($ticket->status->value == 2)
                                                                <div class="badge badge-pill badge-success badge-md">پاسخ
                                                                    ادمین</div>
                                                            @elseif($ticket->status->value == 3)
                                                                <div class="badge badge-pill badge-warning badge-md">پاسخ
                                                                    کاربر</div>
                                                            @else
                                                                <div class="badge badge-pill badge-danger badge-md">بسته شده
                                                                </div>
                                                            @endif
                                                        </td>

                                                        <td class="text-center" style="white-space: nowrap">
                                                            <button data-comment="{{ $ticket->id }}"
                                                                data-action="{{ route('admin.tickets.destroy', ['ticket' => $ticket]) }}"
                                                                type="button"
                                                                class="btn btn-danger mr-1 waves-effect waves-light btn-delete"
                                                                data-toggle="modal" data-target="#delete-modal">حذف
                                                            </button>
                                                            <button data-comment="{{ $ticket->id }}"
                                                                data-action="{{ route('admin.tickets.close', ['ticket' => $ticket]) }}"
                                                                type="button"
                                                                class="btn btn-danger mr-1 waves-effect waves-light btn-close"
                                                                data-toggle="modal" data-target="#close-modal">بستن تیکت
                                                            </button>
                                                            @if ($ticket->messages->count() > 0)
                                                                    <a href="{{ route('admin.tickets.replies', ['ticket' => $ticket]) }}"
                                                                        class="btn btn-primary mr-1 waves-effect waves-light replies">
                                                                        مشاهده
                                                                    </a>
                                                            @endif
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
                                <h4 class="card-title">مدیریت دیدگاه ها</h4>
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
                    {{ $tickets->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- delete ticket modal --}}
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
                    با حذف تیکت دیگر قادر به بازیابی آن نخواهید بود و تمامی پاسخ های آن نیز حذف خواهند شد.
                </div>
                <div class="modal-footer">
                    <form action="#" id="comment-delete-form">
                        @csrf
                        @method('delete')
                        <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal">
                            خیر
                        </button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light">بله حذف شود</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- close ticket modal --}}
    <div class="modal fade text-left" id="close-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel20">آیا مطمئن هستید؟</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    با بستن تیکت دیگر قادر به باز کردن آن نخواهید بود.
                </div>
                <div class="modal-footer">
                    <form action="#" id="ticket-close-form">
                        @csrf
                        @method('post')
                        <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal">
                            خیر
                        </button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light">بله بسته شود</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('public/back/app-assets/plugins/autosize-js/autosize.min.js') }}"></script>
    <script src="{{ asset('public/back/assets/js/pages/tickets/index.js') }}"></script>
@endpush
