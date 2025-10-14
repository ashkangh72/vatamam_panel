@foreach ($options as $option)
    @switch($option['input-type'])
        @case('input')
            @include('back.widgets.partials.input')
        @break

        @case('select')
            @include('back.widgets.partials.select')
        @break

        @case('file')
            @include('back.widgets.partials.file')
        @break

        @case('auctions')
            @include('back.widgets.partials.auctions')
        @break

        @case('products')
            @include('back.widgets.partials.products')
        @break

        @case('categories')
            @include('back.widgets.partials.categories')
        @break

        @case('historical_period')
            @include('back.widgets.partials.historical_period')
        @break

        @case('originality')
            @include('back.widgets.partials.originality')
        @break

        @case('condition')
            @include('back.widgets.partials.condition')
        @break

        @case('timezone')
            @include('back.widgets.partials.timezone')
        @break
    @endswitch
@endforeach
