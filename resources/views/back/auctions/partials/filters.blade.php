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
            <div class="auctions-list-filter">
                <form id="filter-auctions-form" method="GET"
                      action="{{ route('admin.auctions.index') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <label>عنوان</label>
                            <fieldset class="form-group">
                                <input class="form-control datatable-filter" name="title" value="{{ request('title') }}">
                            </fieldset>
                        </div>
                        <div class="col-md-3">
                            <label>شناسه</label>
                            <fieldset class="form-group">
                                <input class="form-control datatable-filter" name="sku" value="{{ request('squ') }}">
                            </fieldset>
                        </div>


                    </div>
                    <div class="row">
                        <div class="col-md-3">

                            <fieldset class="form-group">
                                <button id="filter-auction-btn" class="btn btn-primary">فیلتر</button>
                            </fieldset>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
