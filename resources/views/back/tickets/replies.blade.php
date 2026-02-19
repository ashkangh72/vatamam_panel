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
                @if ($ticketMessages->count())
                    <!-- Ticket Header Card -->
                    <div class="card ticket-header-card mb-2">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <div class="ticket-info">
                                    <h4 class="card-title mb-1">
                                        <i class="fas fa-ticket-alt ml-1"></i>
                                        {{ $ticket->title }}
                                    </h4>
                                    <div class="ticket-meta">
                                        <span class="badge badge-light-primary mr-50">
                                            <i class="fas fa-user ml-25"></i>
                                            {{ $ticket->user->name }}
                                        </span>
                                        <span class="badge badge-light-primary">
                                            <i class="fas fa-clock ml-25"></i>
                                            {{ verta($ticket->created_at)->format('%d %B %Y') }}
                                        </span>
                                    </div>
                                </div>
                                <button type="button" data-ticket="{{ $ticket->id }}"
                                    class="btn btn-primary reply-ticket waves-effect waves-light">
                                    <i class="fas fa-reply ml-1"></i>
                                    پاسخ به تیکت
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Chat Messages Section -->
                    <div class="card chat-messages-card">
                        <div class="card-body">
                            <div class="chat-messages-container" id="chatMessages">
                                @foreach ($ticketMessages as $message)
                                    @if ($message->from == 'user')
                                        <!-- User Message (Right side) -->
                                        <div class="message-row message-row-right">
                                            <div class="message-wrapper">
                                                <div class="message-avatar">
                                                    <div class="avatar bg-light-primary">
                                                        <span class="avatar-content">{{ substr($ticket->user->name, 0, 2) }}</span>
                                                    </div>
                                                </div>
                                                <div class="message-content-wrapper">
                                                    <div class="message-header">
                                                        <span class="message-sender">{{ $ticket->user->name }}</span>
                                                        <span class="message-time">
                                                            <i class="far fa-clock ml-1"></i>
                                                            {{ verta($message->created_at)->format('H:i - %d %B %Y') }}
                                                        </span>
                                                    </div>
                                                    <div class="message-content">
                                                        <p>{{ $message->message }}</p>
                                                        
                                                        @if ($message->first_attachment || $message->second_attachment)
                                                            <div class="message-attachments">
                                                                @if ($message->first_attachment)
                                                                    <a href="{{ $message->first_attachment }}" target="_blank" class="attachment-link">
                                                                        <i class="fas fa-paperclip ml-1"></i>
                                                                         پیوست اول
                                                                    </a>
                                                                @endif
                                                                @if ($message->second_attachment)
                                                                    <a href="{{ $message->second_attachment }}" target="_blank" class="attachment-link mr-1">
                                                                        <i class="fas fa-paperclip ml-1"></i>
                                                                         پیوست دوم
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <!-- Admin Message (Left side) -->
                                        <div class="message-row message-row-left">
                                            <div class="message-wrapper">
                                                <div class="message-avatar">
                                                    <div class="avatar bg-light-success">
                                                        <span class="avatar-content">
                                                            <i class="fas fa-headset"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="message-content-wrapper">
                                                    <div class="message-header">
                                                        <span class="message-sender">پشتیبانی وتمام</span>
                                                        <span class="message-time">
                                                            <i class="far fa-clock ml-1"></i>
                                                            {{ verta($message->created_at)->format('H:i - %d %B %Y') }}
                                                        </span>
                                                    </div>
                                                    <div class="message-content">
                                                        <p>{{ $message->message }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Quick Reply Section -->
                    <div class="card quick-reply-card mt-2">
                        <div class="card-body">
                            <form id="quick-reply-form" action="{{ route('admin.tickets.reply', ['ticket' => $ticket]) }}" class="quick-reply-form">
                                @csrf
                                <div class="form-group mb-0">
                                    <div class="position-relative">
                                        <textarea name="replay" class="form-control auto-expand" rows="1" placeholder="پاسخ خود را بنویسید..."></textarea>
                                        <button type="submit" class="btn btn-primary send-reply-btn">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="pagination-wrapper">
                        {{ $ticketMessages->appends(request()->all())->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <section class="card">
                        <div class="card-body text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-comments fa-4x text-muted mb-3"></i>
                                <h4 class="text-muted mb-1">هنوز پیامی وجود ندارد</h4>
                                <p class="text-muted mb-3">اولین پاسخ را به این تیکت ارسال کنید</p>
                                <button type="button" data-ticket="{{ $ticket->id }}"
                                    class="btn btn-primary reply-ticket">
                                    <i class="fas fa-reply ml-1"></i>
                                    پاسخ به تیکت
                                </button>
                            </div>
                        </div>
                    </section>
                @endif
            </div>
        </div>
    </div>

    <!-- Reply Modal -->
    <div class="modal fade" id="show-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="ticket-reply-form" action="{{ route('admin.tickets.reply', ['ticket' => $ticket]) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-reply ml-1"></i>
                            پاسخ به تیکت
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <fieldset class="form-group">
                            <label for="reply-message">متن پاسخ</label>
                            <textarea name="replay" id="reply-message" class="form-control" rows="5" placeholder="متن پاسخ خود را وارد کنید..."></textarea>
                        </fieldset>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check ml-1"></i>
                            ارسال پاسخ
                        </button>
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                            <i class="fas fa-times ml-1"></i>
                            انصراف
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<!-- Font Awesome 5 CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
/* Chat Messages Styling */
.chat-messages-card {
    border: none;
    box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.1);
}

.chat-messages-container {
    max-height: 600px;
    overflow-y: auto;
    padding: 1rem;
}

/* Message Rows */
.message-row {
    margin-bottom: 1.5rem;
    animation: fadeIn 0.3s ease-in-out;
}

.message-row-left .message-wrapper {
    display: flex;
    align-items: flex-start;
}

.message-row-right .message-wrapper {
    display: flex;
    align-items: flex-start;
    flex-direction: row-reverse;
}

/* Message Avatar */
.message-avatar {
    margin-left: 1rem;
    margin-right: 1rem;
    flex-shrink: 0;
}

.message-row-right .message-avatar {
    margin-left: 1rem;
    margin-right: 1rem;
}

.avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 14px;
}

.avatar.bg-light-primary {
    background: rgba(115, 103, 240, 0.12);
    color: #7367f0;
}

.avatar.bg-light-success {
    background: rgba(40, 199, 111, 0.12);
    color: #28c76f;
}

/* Message Content */
.message-content-wrapper {
    max-width: 70%;
}

.message-row-left .message-content-wrapper {
    margin-left: 0;
}

.message-row-right .message-content-wrapper {
    margin-right: 0;
}

.message-header {
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.message-row-left .message-header {
    justify-content: flex-start;
}

.message-row-right .message-header {
    justify-content: flex-end;
}

.message-sender {
    font-weight: 600;
    font-size: 0.9rem;
    color: #5e5873;
}


.message-time {
    font-size: 0.75rem;
    color: #b9b9c3;
}


.message-content {
    padding: 1rem;
    border-radius: 0.5rem;
    position: relative;
    word-wrap: break-word;
}

.message-row-left .message-content {
    background: #f8f8f8;
    border-top-right-radius: 0;
}

.message-row-right .message-content {
    background: #7367f0;
    color: white;
    border-top-left-radius: 0;
}

.message-content p {
    margin-bottom: 0.5rem;
    line-height: 1.6;
}

.message-content p:last-child {
    margin-bottom: 0;
}

/* Message Attachments */
.message-attachments {
    margin-top: 0.75rem;
    padding-top: 0.75rem;
    border-top: 1px solid rgba(0, 0, 0, 0.05);
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.message-row-right .message-attachments {
    border-top-color: rgba(255, 255, 255, 0.1);
}

.attachment-link {
    display: inline-flex;
    align-items: center;
    padding: 0.35rem 1rem;
    background: rgba(0, 0, 0, 0.05);
    border-radius: 2rem;
    font-size: 0.85rem;
    color: inherit;
    text-decoration: none;
    transition: all 0.2s;
}

.message-row-right .attachment-link {
    background: rgba(255, 255, 255, 0.15);
    color: white;
}

.attachment-link:hover {
    background: rgba(0, 0, 0, 0.1);
    transform: translateY(-1px);
    text-decoration: none;
}

.message-row-right .attachment-link:hover {
    background: rgba(255, 255, 255, 0.25);
}

.attachment-link i {
    font-size: 0.8rem;
    margin-left: 0.35rem;
}

/* Quick Reply Card */
.quick-reply-card {
    border: none;
    box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.1);
}

.quick-reply-form .position-relative {
    display: flex;
    align-items: center;
    position: relative;
}

.quick-reply-form textarea {
    padding-left: 3.5rem;
    padding-right: 1rem;
    resize: none;
    border-radius: 2rem;
    border: 1px solid #d8d6de;
    transition: all 0.2s;
    width: 100%;
    min-height: 45px;
    max-height: 150px;
}

.quick-reply-form textarea:focus {
    border-color: #7367f0;
    box-shadow: 0 0 0 0.2rem rgba(115, 103, 240, 0.25);
    outline: none;
}

.quick-reply-form textarea::placeholder {
    color: #b9b9c3;
}

.send-reply-btn {
    position: absolute;
    left: 5px;
    top: 50%;
    transform: translateY(-50%);
    border-radius: 2rem;
    padding: 0.5rem 1.2rem;
    min-width: auto;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}

.send-reply-btn:hover {
    background: #5e50ee;
    transform: translateY(-50%) scale(1.05);
}

.send-reply-btn i {
    font-size: 1rem;
}

/* Ticket Header Card */
.ticket-header-card {
    border: none;
    box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.1);
    background: linear-gradient(118deg, #7367f0, #9e95f5);
    color: white;
}

.ticket-header-card .card-title {
    color: white;
    font-size: 1.3rem;
}

.ticket-header-card .card-title i {
    font-size: 1.2rem;
}

.ticket-meta {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-top: 0.5rem;
}

.ticket-meta .badge {
    padding: 0.6rem 1.2rem;
    font-size: 0.85rem;
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: none;
    font-weight: 500;
}

.ticket-meta .badge i {
    font-size: 0.8rem;
}

/* Empty State */
.empty-state {
    padding: 2rem;
}

.empty-state i {
    opacity: 0.5;
    color: #b9b9c3;
}

.empty-state h4 {
    color: #5e5873;
}

/* Pagination */
.pagination-wrapper {
    margin-top: 2rem;
    text-align: center;
}

.pagination-wrapper .pagination {
    justify-content: center;
    display: inline-flex;
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* RTL Specific Fixes */
[dir="rtl"] .message-row-left .message-content {
    border-top-right-radius: 0.5rem;
    border-top-left-radius: 0;
}

[dir="rtl"] .message-row-right .message-content {
    border-top-left-radius: 0.5rem;
    border-top-right-radius: 0;
}

[dir="rtl"] .quick-reply-form textarea {
    padding-left: 3.5rem;
    padding-right: 1rem;
}

[dir="rtl"] .send-reply-btn {
    left: 5px;
    right: auto;
}

[dir="rtl"] .ml-1 {
    margin-right: 0.25rem !important;
    margin-left: 0 !important;
}

[dir="rtl"] .ml-25 {
    margin-right: 0.25rem !important;
    margin-left: 0 !important;
}

[dir="rtl"] .mr-50 {
    margin-left: 0.5rem !important;
    margin-right: 0 !important;
}

[dir="rtl"] .mr-1 {
    margin-left: 1rem !important;
    margin-right: 0 !important;
}

/* Scrollbar Styling */
.chat-messages-container::-webkit-scrollbar {
    width: 6px;
}

.chat-messages-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.chat-messages-container::-webkit-scrollbar-thumb {
    background: #7367f0;
    border-radius: 10px;
}

.chat-messages-container::-webkit-scrollbar-thumb:hover {
    background: #5e50ee;
}

/* Responsive */
@media (max-width: 768px) {
    .message-content-wrapper {
        max-width: 85%;
    }
    
    .message-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
    
    .message-row-right .message-header {
        align-items: flex-end;
    }
    
    .message-time {
        margin: 0;
    }
    
    .ticket-header-card .card-body {
        flex-direction: column;
        text-align: center;
    }
    
    .ticket-meta {
        justify-content: center;
    }
    
    .ticket-header-card .btn {
        margin-top: 1rem;
        width: 100%;
    }
    
    .avatar {
        width: 35px;
        height: 35px;
        font-size: 12px;
    }
}

@media (max-width: 480px) {
    .message-content-wrapper {
        max-width: 100%;
    }
    
    .message-row-left .message-wrapper,
    .message-row-right .message-wrapper {
        gap: 0.5rem;
    }
    
    .message-avatar {
        margin-left: 0.5rem;
        margin-right: 0.5rem;
    }
    
    .message-content {
        padding: 0.75rem;
    }
}
</style>
@endpush

@push('scripts')
<!-- Font Awesome 5 (fallback) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>

<script>
    $(document).ready(function() {
        // Auto-expand textarea
        $('.auto-expand').on('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 150) + 'px';
        });

        // Scroll to bottom of chat
        function scrollToBottom() {
            var chatContainer = document.getElementById('chatMessages');
            if (chatContainer) {
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }
        }
        
        // Scroll to bottom on page load
        setTimeout(scrollToBottom, 100);

        // Handle reply button click
        $(document).on('click', '.reply-ticket', function() {
            $('#show-modal').modal('show');
        });

        // Handle quick reply form submission
        $('#quick-reply-form').submit(function(e) {
            e.preventDefault();
            submitReplyForm($(this));
        });

        // Handle modal reply form submission
        $('#ticket-reply-form').submit(function(e) {
            e.preventDefault();
            submitReplyForm($(this));
        });

        // Function to submit reply form
        function submitReplyForm(form) {
            var formData = new FormData(form[0]);
            var submitBtn = form.find('[type="submit"]');
            var originalText = submitBtn.html();
            
            // Disable button and show loading
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> در حال ارسال...');
            
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                success: function(data) {
                    toastr.success('پاسخ با موفقیت ارسال شد.');
                    $('#show-modal').modal('hide');
                    
                    // Clear form
                    form.find('textarea').val('');
                    
                    // Reload after a short delay
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                },
                error: function(xhr) {
                    // Restore button
                    submitBtn.prop('disabled', false).html(originalText);
                    
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        if (errors && errors.replay) {
                            toastr.error(errors.replay[0]);
                        } else {
                            toastr.error('لطفا متن پاسخ را وارد کنید');
                        }
                    } else {
                        toastr.error('خطا در ارتباط با سرور');
                    }
                },
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                },
                cache: false,
                contentType: false,
                processData: false
            });
        }
        
        // Handle enter key in quick reply (Ctrl+Enter or Cmd+Enter to submit)
        $('#quick-reply-form textarea').on('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.keyCode === 13) {
                $('#quick-reply-form').submit();
            }
        });
        
        // Reset textarea height on modal hide
        $('#show-modal').on('hidden.bs.modal', function() {
            $(this).find('textarea').val('');
        });
    });
</script>
<script src="{{ asset('public/back/app-assets/plugins/autosize-js/autosize.min.js') }}"></script>
@endpush