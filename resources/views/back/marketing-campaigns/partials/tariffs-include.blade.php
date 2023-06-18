<div class="row single-tariff animated fadeIn">
    <div class="col-md-3 col-12">
        <div class="form-group">
            <label>حداقل خرید</label>
            <input type="number" min="0" class="form-control minimum_purchase"
                   name="tariffs[{{ $loop->index }}][minimum_purchase]" value="{{ $tariff->minimum_purchase }}">
        </div>
    </div>
    <div class="col-md-3 col-12">
        <div class="form-group">
            <label>کمیسیون محصولات</label>
            <input type="number" min="0" max="100" class="form-control products_commission_percent"
                   name="tariffs[{{ $loop->index }}][products_commission_percent]" placeholder="%" value="{{ $tariff->products_commission_percent }}">
        </div>
    </div>
    <div class="col-md-3 col-12">
        <div class="form-group">
            <label>کمیسیون محصولات تخفیفی</label>
            <input type="number" min="0" max="100" class="form-control discounted_products_commission_percent"
                   name="tariffs[{{ $loop->index }}][discounted_products_commission_percent]" placeholder="%" value="{{ $tariff->discounted_products_commission_percent }}">
        </div>
    </div>
    <div class="col-md-3 col-12">
        <div class="form-group">
            <label>کمیسیون سفارش تخفیفی</label>
            <input type="number" min="0" max="100" class="form-control discounted_orders_commission_percent"
                   name="tariffs[{{ $loop->index }}][discounted_orders_commission_percent]" placeholder="%" value="{{ $tariff->discounted_orders_commission_percent }}">
        </div>
    </div>

    <div class="col-md-12 col-12">
        <button type="button" class="btn btn-flat-danger waves-effect waves-light btn-block text-center remove-tariff">حذف</button>
        <hr>
    </div>
</div>
