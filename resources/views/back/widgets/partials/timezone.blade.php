<div class="{{ $option['class'] ?? 'col-md-6 col-12' }}">
    <div class="form-group">
        <label>{{ $option['title'] }}</label>
        <select class="form-control" name="options[{{ $option['key'] }}]" {!! $option['attributes'] ?? '' !!}>
            <option value>انتخاب کنید...</option>
            @foreach (\App\Enums\AuctionTimezoneEnum::cases() as $timezone)

                @php
                    $selected = isset($widget) && $timezone->value == $option['key'];
                @endphp

                <option value="{{ $timezone->value }}" {{ $selected ? 'selected' : '' }}>{{ \App\Enums\AuctionTimezoneEnum::getTitle($timezone) }}</option>
            @endforeach
        </select>
    </div>
</div>
