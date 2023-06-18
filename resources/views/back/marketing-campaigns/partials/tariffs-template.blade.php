<script id="tariffs-template" type="text/x-custom-template">
    <div class="row single-tariff animated fadeIn">
        <div class="col-md-3 col-12">
            <div class="form-group">
                <label>حداقل خرید</label>
                <input type="number" min="0" class="form-control minimum_purchase"
                       name="minimum_purchase" value="{{ old('minimum_purchase') }}">
            </div>
        </div>
        <div class="col-md-3 col-12">
            <div class="form-group">
                <label>کمیسیون محصولات</label>
                <input type="number" min="0" max="100" class="form-control products_commission_percent"
                       name="products_commission_percent" placeholder="%" value="{{ old('products_commission_percent') }}">
            </div>
        </div>
        <div class="col-md-3 col-12">
            <div class="form-group">
                <label>کمیسیون محصولات تخفیفی</label>
                <input type="number" min="0" max="100" class="form-control discounted_products_commission_percent"
                       name="discounted_products_commission_percent" placeholder="%" value="{{ old('discounted_products_commission_percent') }}">
            </div>
        </div>
        <div class="col-md-3 col-12">
            <div class="form-group">
                <label>کمیسیون سفارش تخفیفی</label>
                <input type="number" min="0" max="100" class="form-control discounted_orders_commission_percent"
                       name="discounted_orders_commission_percent" placeholder="%" value="{{ old('discounted_orders_commission_percent') }}">
            </div>
        </div>

        <div class="col-md-12 col-12">
            <button type="button" class="btn btn-flat-danger waves-effect waves-light btn-block text-center remove-tariff">حذف</button>
            <hr>
        </div>
    </div>
</script>
