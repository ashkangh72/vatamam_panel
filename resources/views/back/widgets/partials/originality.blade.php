<div class="{{ $option['class'] ?? 'col-md-6 col-12' }}">
    <div class="form-group">
        <label>{{ $option['title'] }}</label>
        <select class="form-control" name="options[{{ $option['key'] }}]" {!! $option['attributes'] ?? '' !!}>
            <option value>انتخاب کنید...</option>
            @foreach ($originality as $item)

                @php
                    $selected = isset($widget) && $item->id == $option['key'];
                @endphp

                <option value="{{ $item->id }}" {{ $selected ? 'selected' : '' }}>{{ $item->title }}</option>
            @endforeach
        </select>
    </div>
</div>
