@if($slides->count())
    <section class="card slides-sortable">
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
                        <tbody id="slides-sortable-{{ $loop->index }}">
                        @foreach($slides as $slide)
                            <tr id="slide-{{ $slide->id }}">
                                <td class="text-center draggable-handler">
                                    <div class="fonticon-wrap"><i class="feather icon-move"></i></div>
                                </td>
                                <td>
                                    <div>
                                        <img height="100px" src="{{ $slide->image }}" alt="{{ $slide->title ?: '--' }}">
                                    </div>
                                </td>
                                <td>{{ $slide->title ?: '--' }}</td>
                                <td class="text-center">
                                    @if($slide->is_active)
                                        <div class="badge badge-pill badge-success badge-md">منتشر شده</div>
                                    @else
                                        <div class="badge badge-pill badge-danger badge-md">پیش نویس</div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @can('slides.update')
                                        <a href="{{ route('admin.slides.edit', ['slide' => $slide]) }}"
                                           class="btn btn-info mr-1 waves-effect waves-light">ویرایش</a>
                                    @endcan
                                    @can('slides.delete')
                                        <button type="button" data-slide="{{ $slide->id }}"
                                                class="btn btn-danger mr-1 waves-effect waves-light btn-delete"
                                                data-toggle="modal" data-target="#delete-modal">حذف
                                        </button>
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
