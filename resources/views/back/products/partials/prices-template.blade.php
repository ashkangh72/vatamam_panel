<script id="prices-template" type="text/x-custom-template">
    <div class="container single-price">
        <div class="hidden">
            <select name="color_attribute"></select>
        </div>
        <div class="col-md-2 col-6">
            <div class="form-group">
                <label class="label">اندازه</label>
                <select class="form-control disabled" name="size_attribute"></select>
            </div>
        </div>

        <div class="col-md-2 col-6">
            <div class="form-group">
                <label class="label">موجودی انبار</label>
                <input type="number" class="form-control" name="stock" min="0" required>
            </div>
        </div>

        <div class="col-md-2 col-6">
            <div class="form-group">
                <label class="label">قیمت</label>
                <input type="number" class="form-control amount-input" name="price" min="100" required>
            </div>
        </div>

        <div class="col-md-2 col-6">
            <div class="form-group">
                <label class="label">تخفیف</label>
                <input type="number" class="form-control discount-input" name="discount" min="0" max="100" placeholder="%">
            </div>
        </div>

        <div class="col-md-2 col-6">
            <div class="form-group">
                <label class="label">قیمت با تخفیف</label>
                <input type="number" class="form-control discount-price-input" name="discount_price" min="100">
            </div>
        </div>

        {{-- <div class="col-md-3 col-6">
            <div class="form-group">
                <label>تخفیف زمان دار</label>
                <input autocomplete="off" id="ctv" type="text" class="form-control publish_date_picker_discount" >
                <input type="hidden" name="discount_expire" id="publish_date">
            </div>
        </div> --}}

        <div class="col-md-2 col-6">
            <label class="label"></label>
            <button type="button" class="btn btn-flat-danger waves-effect waves-light remove-product-price remove-product-price-button"><i class="ficon feather icon-x-circle"></i></button>
        </div>
    </div>
</script>
