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
                                    <li class="breadcrumb-item active">مدیریت دیدگاه ها</li>
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
                                                $pending = \App\Enums\CommentStatusEnum::pending->value;
                                                $approved = \App\Enums\CommentStatusEnum::approved->value;
                                                $rejected = \App\Enums\CommentStatusEnum::rejected->value;
                                            @endphp
                                            <fieldset class="form-group">
                                                <select class="form-control" name="status" id="filter-status">
                                                    <option value="">همه</option>
                                                    <option value="{{ $pending }}"
                                                        {{ (int) request('status') == $pending ? 'selected' : '' }}>
                                                        منتظر تایید
                                                    </option>
                                                    <option value="{{ $approved }}"
                                                        {{ (int) request('status') == $approved ? 'selected' : '' }}>
                                                        تایید شده
                                                    </option>
                                                    <option value="{{ $rejected }}"
                                                        {{ (int) request('status') == $rejected ? 'selected' : '' }}>
                                                        رد شده
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
                    @if ($comments->count())
                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">مدیریت دیدگاه ها</h4>
                            </div>
                            <div class="card-content" id="main-card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">#</th>
                                                    <th>نام</th>
                                                    <th>دیدگاه</th>
                                                    <th class="text-center">وضعیت</th>
                                                    <th class="text-center">عملیات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($comments as $comment)
                                                    <tr id="comment-{{ $comment->id }}-tr">
                                                        <td class="text-center">
                                                            {{ $comment->id }}
                                                        </td>
                                                        <td style="white-space: nowrap">{{ $comment->user->name ?: '---' }}
                                                        </td>
                                                        <td style="max-width: 300px">
                                                            {{ short_content($comment->body, 20, false) }}</td>
                                                        <td class="text-center">
                                                            @if ((int) $comment->status == $pending)
                                                                <div class="badge badge-pill badge-warning badge-md">منتظر
                                                                    تایید</div>
                                                            @elseif((int) $comment->status == $approved)
                                                                <div class="badge badge-pill badge-success badge-md">تایید
                                                                    شده</div>
                                                            @else
                                                                <div class="badge badge-pill badge-danger badge-md">رد شده
                                                                </div>
                                                            @endif
                                                        </td>

                                                        <td class="text-center" style="white-space: nowrap">
                                                            @can('comments.show')
                                                                <button type="button" data-comment="{{ $comment->id }}"
                                                                    class="btn btn-success mr-1 waves-effect waves-light show-comment">
                                                                    مشاهده
                                                                </button>
                                                            @endcan
                                                            @can('comments.delete')
                                                                <button data-comment="{{ $comment->id }}"
                                                                    data-action="{{ route('admin.comments.destroy', ['comment' => $comment]) }}"
                                                                    type="button"
                                                                    class="btn btn-danger mr-1 waves-effect waves-light btn-delete"
                                                                    data-toggle="modal" data-target="#delete-modal">حذف
                                                                </button>
                                                            @endcan
                                                            @can('comments.answers')
                                                                @if ($comment->comments->count() > 0)
                                                                    <div class="mt-1">
                                                                        <button data-comment="{{ $comment->id }}"
                                                                            data-action="{{ route('admin.comments.replies', ['comment' => $comment]) }}"
                                                                            data-toggle="collapse" data-target="#replies"
                                                                            type="button"
                                                                            class="btn btn-primary mr-1 waves-effect waves-light replies">
                                                                            پاسخ ها
                                                                        </button>
                                                                    </div>
                                                                @endif
                                                            @endcan
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
                    {{ $comments->appends(request()->all())->links() }}
                </div>
                <div class="card">
                    <div class="collapse" id="replies">

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- delete comment modal --}}
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
                    با حذف دیدگاه دیگر قادر به بازیابی آن نخواهید بود و تمامی پاسخ های آن نیز حذف خواهند شد.
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

    <!-- show Modal -->
    <!-- Modal -->
    <div class="modal fade" id="show-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">مشاهده جرئیات</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="comment-detail" class="modal-body">

                </div>
                <div class="modal-footer">
                    <button id="comment-form-submit-btn" type="button" class="btn btn-outline-success">ذخیره</button>
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">بستن</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('public/back/app-assets/plugins/autosize-js/autosize.min.js') }}"></script>
    <script src="{{ asset('public/back/assets/js/pages/comments/index.js') }}"></script>
@endpush
