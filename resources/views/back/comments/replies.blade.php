@if($comments->count())
    <section class="card">
        <div class="card-header">
            <h4 class="card-title">پاسخ ها</h4>
        </div>
        <div class="card-content" >
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>نام</th>
                            <th>عنوان</th>
                            <th>دیدگاه</th>
{{--                            <th>نوع دیدگاه</th>--}}
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
                                <td>{{ $comment->user ? $comment->user->fullname : $comment->name }}</td>
                                <td style="max-width: 300px">{{ short_content($comment->title, 20, false) }}</td>
                                <td style="max-width: 300px">{{ short_content($comment->body, 20, false) }}</td>
                                <td class="text-center">
                                    @if($comment->status == 'pending')
                                        <div class="badge badge-pill badge-warning badge-md">منتظر تایید</div>
                                    @elseif($comment->status == 'accepted')
                                        <div class="badge badge-pill badge-success badge-md">تایید شده</div>
                                    @else
                                        <div class="badge badge-pill badge-danger badge-md">تایید نشده</div>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <button type="button" data-comment="{{ $comment->id }}" class="btn btn-success mr-1 waves-effect waves-light show-comment">مشاهده</button>
                                    <button data-comment="{{ $comment->id }}" data-action="{{ route('admin.comments.destroy', ['comment' => $comment]) }}" type="button" class="btn btn-danger mr-1 waves-effect waves-light btn-delete"  data-toggle="modal" data-target="#delete-modal">حذف</button>
                                </td>
                            </tr>
                            @if($comment->comments)
                                @include("back.comments.replies",['comments'=>$comment->comments])
                            @endif
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

@endif
