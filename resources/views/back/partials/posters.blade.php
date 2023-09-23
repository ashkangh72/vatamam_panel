@if($posters->count())
    <section class="card posters-sortable">
        <div class="card-header">
            <h4 class="card-title">{{ $title }}</h4>
        </div>
        <div class="card-content">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th class="text-center">ردیف</th>
                                <th>تصویر</th>
                                <th>عنوان</th>
                                <th class="text-center">وضعیت</th>
                                <th class="text-center">عملیات</th>
                            </tr>
                        </thead>
                        <tbody id="posters-sortable-{{ $loop->index }}">
                            @foreach($posters as $poster)
                                <tr id="poster-{{ $poster->id }}">
                                    <td class="text-center draggable-handler">
                                        <div class="fonticon-wrap"><i class="feather icon-move"></i></div>
                                    </td>
                                    <td>
                                        <div class="slider-thumb">
                                            <img src="{{ asset($poster->image) }}" alt="poster image">
                                        </div>
                                    </td>
                                    <td>{{ $poster->title ?: '--' }}</td>
                                    <td class="text-center">
                                        @if($poster->is_active)
                                            <div class="badge badge-pill badge-success badge-md">منتشر شده</div>
                                        @else
                                            <div class="badge badge-pill badge-danger badge-md">پیش نویس</div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @can('posters.update')
                                            <a href="{{ route('admin.posters.edit', ['poster' => $poster]) }}" class="btn btn-info mr-1 waves-effect waves-light">ویرایش</a>
                                        @endcan

                                        @can('posters.delete')
                                            <button type="button" data-poster="{{ $poster->id }}" class="btn btn-danger mr-1 waves-effect waves-light btn-delete"  data-toggle="modal" data-target="#delete-modal">حذف</button>
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
            <h4 class="card-title">{{ $title }}</h4>
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
