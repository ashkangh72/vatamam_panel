<div class="container single-price">
    @foreach ($attributeGroups as $attributeGroup)
        @php
            $attribute = $price->get_attributes()->where('attribute_group_id', $attributeGroup->id)->first()
        @endphp
        <div class="@if($loop->iteration == 1 ) hidden @else col-md-2 col-6 @endif">
            <div class="form-group">
                @if($loop->parent->iteration == 1)<label class="label">{{ $attributeGroup->name }}</label>@endif
                <select class="form-control disabled" name="prices[{{ $iteration }}][attributes][]"
                        @if($loop->iteration == 1) id="price-{{ $colorAttributeId }}-{{ $sizeAttributeId }}" @endif>
                    <option value="{{ $attribute->id }}" selected>{{ $attribute->name }}</option>
                </select>
            </div>
        </div>
    @endforeach

    <div class="col-md-2 col-6">
        <div class="form-group">
            @if($loop->iteration == 1)<label class="label">موجودی انبار</label>@endif
            <input type="number" class="form-control" name="prices[{{ $iteration }}][stock]" value="{{ $price->stock }}" min="0" required>
        </div>
    </div>

    <div class="col-md-2 col-6">
        <div class="form-group">
            @if($loop->iteration == 1)<label class="label">قیمت</label>@endif
            <input type="number" class="form-control amount-input" name="prices[{{ $iteration }}][price]" value="{{ $price->price }}" min="100" required>
        </div>
    </div>

    <div class="col-md-2 col-6">
        <div class="form-group">
            @if($loop->iteration == 1)<label class="label">تخفیف</label>@endif
            <input type="number" class="form-control discount-input" name="prices[{{ $iteration }}][discount]" value="{{ $price->discount }}" min="0" max="100" placeholder="%">
        </div>
    </div>

    <div class="col-md-2 col-6">
        <div class="form-group">
            @if($loop->iteration == 1)<label class="label">قیمت با تخفیف</label>@endif
            <input type="number" class="form-control discount-price-input {{!$price->discount?'text-muted':''}}" name="prices[{{ $iteration }}][discount_price]" value="{{ $price->discount_price }}" min="100">
        </div>
    </div>

    {{-- <div class="col-md-3 col-6">
        <div class="form-group">
            <label class="label">تخفیف زمان دار</label>
            <input autocomplete="off"  type="text" class="form-control publish_date_picker_discount" id="publish_date_picker1" value="{{ $price->discount_expire_at ? verta($price->discount_expire_at) : '' }}">
            <input type="hidden" name="prices[{{ $loop->iteration }}][discount_expire]" id="publish_date1" value="{{ $price->discount_expire_at ? verta($price->discount_expire_at) : '' }}">
        </div>
    </div> --}}

    <div class="col-md-2 col-6 mt-2">
        <button type="button" class="btn btn-flat-danger waves-effect waves-light remove-product-price @if($loop->iteration > 1) remove-product-price-button-include @else remove-product-price-button @endif"><i class="ficon feather icon-x-circle"></i></button>
    </div>
</div>
