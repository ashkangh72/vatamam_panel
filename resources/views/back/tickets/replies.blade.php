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

                <div class="list-comments">
                    @if ($ticketMessages->count())
                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">مدیریت تیکت {{ $ticket->user->name }} با عنوان {{ $ticket->title }}
                                </h4>
                                <button type="button" data-ticket="{{ $ticket->id }}"
                                    class="btn btn-success mr-1 waves-effect waves-light reply-ticket">
                                    پاسخ تیکت
                                </button>
                            </div>
                            <div class="card-content" id="main-card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        @foreach ($ticketMessages as $message)
                                            <div class="row">
                                                <div class="col-12">
                                                    @if ($message->from == 'user')
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="card user-statistics-card">
                                                                    <div class="card-header d-flex align-items-start pb-0">
                                                                        <div>
                                                                            <h2 class="text-bold-700 mb-0">
                                                                                {{ $message->message }}</h2>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-footer">
                                                                        @if ($message->first_attachment)
                                                                            <span>
                                                                                <a href="{{ $message->first_attachment }}"
                                                                                    target="_blank" class="card-link">پیوست
                                                                                    <i class="fa fa-angle-left"></i></a>
                                                                            </span>
                                                                        @endif
                                                                        @if ($message->second_attachment)
                                                                            <span>
                                                                                <a href="{{ $message->second_attachment }}"
                                                                                    target="_blank" class="card-link">پیوست
                                                                                    دوم <i class="fa fa-angle-left"></i></a>
                                                                            </span>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="card user-statistics-card">
                                                                    <div class="card-header d-flex align-items-start pb-0">
                                                                        <div class=" bg-rgba-info p-50 m-0">
                                                                            <h2 class="text-bold-700 mb-0">
                                                                                {{ $message->message }}</h2>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-footer">
                                                                        <span>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
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
                    {{ $ticketMessages->appends(request()->all())->links() }}
                </div>
                <div class="card">
                    <div class="collapse" id="replies">

                    </div>
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
                <form id="ticket-reply-form" action="{{ route('admin.tickets.reply', ['ticket' => $ticket]) }}"
                    redirect="{{ route('admin.tickets.index') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">پاسخ تیکت</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="comment-detail" class="modal-body">

                        <fieldset class="form-group">
                            <textarea name="replay" class="form-control" rows="4"></textarea>
                        </fieldset>
                    </div>
                    <div class="modal-footer">
                        <button id="comment-form-submit-btn" type="submit" class="btn btn-outline-success">ذخیره</button>
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">بستن</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).on('click', '.reply-ticket', function() {
            $('#show-modal').modal('show');
        });

        $(document).ready(function() {

            $('#ticket-reply-form').submit(function(e) {
                // e.preventDefault();
                let form = $(this);
                // console.log(this);
                // return;
                var formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    success: function(data) {

                        $('#show-modal').modal('hide');
                        window.location.reload();
                    },
                    beforeSend: function(xhr) {
                        block('#main-card');
                        xhr.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr(
                            'content'));
                    },
                    complete: function() {
                        unblock('#main-card');
                    },

                    cache: false,
                    contentType: false,
                    processData: false
                });
            });
        });
    </script>
    <script src="{{ asset('public/back/app-assets/plugins/autosize-js/autosize.min.js') }}"></script>
    <script src="{{ asset('public/back/assets/js/pages/tickets/index.js') }}"></script>
@endpush
