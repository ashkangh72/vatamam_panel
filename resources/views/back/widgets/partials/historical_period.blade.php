<div class="{{ $option['class'] ?? 'col-md-6 col-12' }}">
    <div class="form-group">
        <label>{{ $option['title'] }}</label>
        <select class="form-control" name="options[{{ $option['key'] }}]" {!! $option['attributes'] ?? '' !!}>
            <option value>انتخاب کنید...</option>
            @foreach ($historicalPeriods as $historicalPeriod)

                @php
                    $selected = isset($widget) && $historicalPeriod->id == $option['key'];
                @endphp

                <option value="{{ $historicalPeriod->id }}" {{ $selected ? 'selected' : '' }}>{{ $historicalPeriod->title }}</option>
            @endforeach
        </select>
    </div>
</div>
