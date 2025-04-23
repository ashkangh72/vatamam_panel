<div class="{{ $option['class'] ?? 'col-md-6 col-12' }}">
    <div class="form-group">
        <label>{{ $option['title'] }}</label>
        <select id="products-{{ $option['key'] }}" class="form-control" name="options[{{ $option['key'] }}][]"
            {!! $option['attributes'] ?? '' !!} multiple>
            @foreach ($products as $auction)
                @php
                    $selected = false;

                    if (isset($widget)) {
                        $widget_option = $widget->options()->where('key', $option['key'])->first();
                        if ($widget_option && $widget_option->auctions()->find($auction->id)) {
                            $selected = true;
                        }
                    }
                @endphp

                <option
                    class="l{{ 0 + 1 }} {{ 1 == 0 ? 'non-leaf' : '' }}"
                    data-pup="{{ $auction->auction_id }}" {{ $selected ? 'selected' : '' }} value="{{ $auction->id }}">
                    {{ $auction->title }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<script>
    setTimeout(() => {
        $("#products-{{ $option['key'] }}").select2ToTree({
            rtl: true,
            width: '100%',
        });
    }, 300);
</script>
