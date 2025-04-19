<div class="table-responsive">
    <form id="comment-edit-form" action="{{ route('admin.comments.update', ['comment' => $comment]) }}">
        @method('put')

        <table class="table">
            <tbody>
            <tr>
                <th scope="row">نام</th>
                <td>
                    @if ($comment->user)
                        {{ $comment->user->name ?: '---' }}
                        <a class="float-right" href="{{ route('admin.users.show', ['user' => $comment->user]) }}"
                           target="_blank"><i class="feather icon-external-link"></i></a>
                    @endif
                </td>
            </tr>
            <tr>
                <th scope="row">مزایده</th>
                <td>{{ $comment->auction->title }}
                    <a class="float-right" href="{{ $comment->auction->getUrl() }}" target="_blank">
                        <i class="feather icon-external-link"></i>
                    </a>
                </td>
            </tr>
            <tr>
                <th scope="row" style="min-width: 100px;">متن دیدگاه</th>
                <td>
                    <div id="comment-body">
                        {{ $comment->body }}
                        <div class="mt-1">
                            <button id="edit-comment-btn" type="button"
                                    class="btn btn-flat-primary waves-effect waves-light"><i
                                    class="feather icon-edit"></i> ویرایش
                            </button>
                        </div>
                    </div>

                    <fieldset id="edit-comment-body" class="form-group" style="display: none;">
                        <textarea name="body" class="form-control" rows="4" required>{{ $comment->body }}</textarea>
                    </fieldset>
                </td>
            </tr>

            @if (!$comment->comment_id)
                <tr>
                    <th scope="row">تعداد پاسخ ها</th>
                    <td>{{ $comment->comments->count() }}</td>
                </tr>
                <tr>
                    <th scope="row">پاسخ</th>
                    <td>
                        <fieldset class="form-group">
                            <textarea name="replay" class="form-control" rows="4"></textarea>
                        </fieldset>
                    </td>
                </tr>
            @endif
            <tr>
                <th scope="row">تاریخ ارسال</th>
                <td>{{ tverta($comment->created_at) }} ( {{ tverta($comment->created_at)->formatDifference() }} )</td>
            </tr>
            <tr>
                <th scope="row">وضعیت</th>
                <td>
                    @php
                        $pending = \App\Enums\CommentStatusEnum::pending->value;
                        $approved = \App\Enums\CommentStatusEnum::approved->value;
                        $rejected = \App\Enums\CommentStatusEnum::rejected->value;
                    @endphp
                    <select class="form-control" name="status">
                        <option value="{{ $pending }}" {{ $comment->status == $pending ? 'selected' : '' }}>منتظر تایید</option>
                        <option value="{{ $approved }}" {{ $comment->status == $approved ? 'selected' : '' }}>تایید شده</option>
                        <option value="{{ $rejected }}" {{ $comment->status == $rejected ? 'selected' : '' }}>رد شده</option>
                    </select>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
</div>
