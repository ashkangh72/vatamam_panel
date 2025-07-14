@if ($child_menu->menus->isEmpty())
    <li data-category-type="{{ $child_menu->type }}" class="dd-item" data-id="{{ $child_menu->id }}">
        <div class="dd-handle"><span class="menu-title">
                {{ $child_menu->title }}
                @if ($child_menu->type == \App\Enums\MenuTypeEnum::category)
                    ( دسته بندی )
                @endif
            </span>
            @can('menus.delete')
                <a data-menu="{{ $child_menu->id }}" class="float-right delete-menu dd-nodrag" href="javascript:void(0)"><i
                        class="fa fa-trash text-danger px-1"></i>حذف</a>
            @endcan
            @can('menus.update')
                <a data-menu="{{ $child_menu->id }}" class="float-right edit-menu dd-nodrag" href="javascript:void(0)"><i
                        class="fa fa-pencil text-info px-1"></i>ویرایش</a>
            @endcan
        </div>
    </li>
@else
    <li data-category-type="{{ $child_menu->type }}" class="dd-item" data-id="{{ $child_menu->id }}">
        <div class="dd-handle"><span class="menu-title">{{ $child_menu->title }}</span>
            @can('menus.delete')
                <a data-menu="{{ $child_menu->id }}" class="float-right delete-menu dd-nodrag" href="javascript:void(0)"><i
                        class="fa fa-trash text-danger px-1"></i>حذف</a>
            @endcan
            @can('menus.update')
                <a data-menu="{{ $child_menu->id }}" class="float-right edit-menu dd-nodrag" href="javascript:void(0)"><i
                        class="fa fa-pencil text-info px-1"></i>ویرایش</a>
            @endcan
        </div>
        <ol class="dd-list">
            @foreach ($child_menu->menus as $childMenu)
                @include('back.menus.partials.child_menu', ['child_menu' => $childMenu])
            @endforeach
        </ol>
    </li>
@endif
