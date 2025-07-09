<li class="dd-item" data-id="{{ $child_category->id }}">
    @if ($child_category->children->isEmpty())
        <div class="dd-handle"><span class="category-title">{{ $child_category->title }}</span>
            @can('categories.delete')
                <a data-category="{{ $child_category->id }}" class="float-right delete-category dd-nodrag"
                    href="javascript:void(0)"><i class="fa fa-trash text-danger px-1"></i>حذف</a>
            @endcan
            @can('categories.update')
                <a data-category="{{ $child_category->id }}" class="float-right edit-category dd-nodrag"
                    href="javascript:void(0)"><i class="fa fa-pencil text-info px-1"></i>ویرایش</a>
            @endcan
        </div>
    @else
        <div class="dd-handle"><span class="category-title">{{ $child_category->title }}</span>
            @can('categories.delete')
                <a data-category="{{ $child_category->id }}" class="float-right delete-category dd-nodrag"
                    href="javascript:void(0)"><i class="fa fa-trash text-danger px-1"></i>حذف</a>
            @endcan
            @can('categories.update')
                <a data-category="{{ $child_category->id }}" class="float-right edit-category dd-nodrag"
                    href="javascript:void(0)"><i class="fa fa-pencil text-info px-1"></i>ویرایش</a>
            @endcan
        </div>
        <ol class="dd-list">
            @foreach ($child_category->children as $childCategory)
                @include('back.partials.child_category', ['child_category' => $childCategory])
            @endforeach
        </ol>
    @endif
</li>
