<div class="{{ $option['class'] ?? 'col-md-6 col-12' }}">
    <div class="form-group">
        <label>{{ $option['title'] }}</label>
        <select class="form-control" name="options[{{ $option['key'] }}]" {!! $option['attributes'] ?? '' !!}>
            <option value>انتخاب کنید...</option>
            @foreach (\App\Enums\AuctionConditionEnum::cases() as $condition)

                @php
                    $selected = isset($widget) && $condition->value == $option['key'];
                @endphp

                <option value="{{ $condition->value }}" {{ $selected ? 'selected' : '' }}>{{ \App\Enums\AuctionConditionEnum::getTitle($condition) }}</option>
            @endforeach
        </select>
    </div>
</div>
