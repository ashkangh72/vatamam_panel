<div class="card">
    <div class="card-header filter-card">
        <h4 class="card-title">فیلتر کردن</h4>
        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
        <div class="heading-elements">
            <ul class="list-inline mb-0">
                <li><a data-action="collapse"><i class="feather icon-chevron-down"></i></a></li>
            </ul>
        </div>
    </div>
    <div class="card-content collapse {{ request()->except('page') ? 'show' : '' }}">
        <div class="card-body">
            <div class="users-list-filter">
                <form id="filter-orders-form" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <label>نام و نام خانوادگی</label>
                            <fieldset class="form-group">
                                <input type="text" class="form-control datatable-filter" name="name" value="{{ request('name') }}">
                            </fieldset>
                        </div>

                        <div class="col-md-3">
                            <label>نام کاربری</label>
                            <fieldset class="form-group">
                                <input class="form-control datatable-filter" name="username" value="{{ request('username') }}">
                            </fieldset>
                        </div>

                        <div class="col-md-2">
                            <label>شماره سفارش</label>
                            <fieldset class="form-group">
                                <input type="text" class="form-control datatable-filter" name="id" value="{{ request('id') }}">
                            </fieldset>
                        </div>
                        <div class="col-md-2">
                            <label>وضعیت</label>
                            <fieldset class="form-group">
                                <select name="status" class="form-control datatable-filter">
                                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>همه</option>
                                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>پرداخت شده</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>جدید</option>
                                    <option value="locked" {{ request('status') == 'locked' ? 'selected' : '' }}>پرداخت نشده</option>
                                </select>
                            </fieldset>
                        </div>
                        <div class="col-md-2">
                            <label>وضعیت ارسال</label>
                            <fieldset class="form-group">
                                <select name="shipping_status" class="form-control datatable-filter">
                                    <option value="all" {{ request('shipping_status') == 'all' ? 'selected' : '' }}>همه</option>
                                    <option value="pending" {{ request('shipping_status') == 'pending' ? 'selected' : '' }}>در حال بررسی</option>
                                    <option value="shipping_request" {{ request('shipping_status') == 'shipping_request' ? 'selected' : '' }}>منتظر ارسال</option>
                                    <option value="shipped" {{ request('shipping_status') == 'shipped' ? 'selected' : '' }}>ارسال شده</option>
                                </select>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <fieldset class="form-group">
                                <button id="filter-orders-btn" class="btn btn-primary">فیلتر</button>
                            </fieldset>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
