@if ($tickets->count())
    <section class="card">
        <div class="card-header">
            <h4 class="card-title">پاسخ ها</h4>
        </div>
        <div class="card-content">
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
                            @foreach ($tickets as $comment)
                                <tr id="comment-{{ $comment->id }}-tr">
                                    <td class="text-center">
                                        {{ $comment->id }}
                                    </td>
                                    <td style="white-space: nowrap">{{ $comment->id ?: '---' }}</td>
                                    <td style="max-width: 300px">{{ short_content($comment->id, 20, false) }}</td>
                                    <td class="text-center">
                                        <button type="button" data-comment="{{ $comment->id }}"
                                            class="btn btn-success mr-1 waves-effect waves-light show-comment">مشاهده
                                        </button>
                                        <button data-comment="{{ $comment->id }}"
                                            data-action="{{ route('admin.comments.destroy', ['comment' => $comment]) }}"
                                            type="button"
                                            class="btn btn-danger mr-1 waves-effect waves-light btn-delete"
                                            data-toggle="modal" data-target="#delete-modal">حذف
                                        </button>
                                    </td>
                                </tr>
                                
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endif
