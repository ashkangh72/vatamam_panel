@foreach ($options as $option)
    @switch($option['input-type'])
        @case('input')
            @include('back.widgets.partials.input')
            @break
        @case('file')
            @include('back.widgets.partials.file')
            @break
        @case('select')
            @include('back.widgets.partials.select')
            @break
        @case('categories')
            @include('back.widgets.partials.categories')
            @break
    @endswitch
@endforeach
