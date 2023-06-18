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
            <div class="faqs-list-filter">
                <form id="filter-faqs-form" method="GET" action="{{ route('admin.faqs.index') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <label>سوال</label>
                            <fieldset class="form-group">
                                <input class="form-control datatable-filter" name="question" value="{{ request('question') }}">
                            </fieldset>
                        </div>
                        <div class="col-md-3">
                            <label>پاسخ</label>
                            <fieldset class="form-group">
                                <input class="form-control datatable-filter" name="answer" value="{{ request('answer') }}">
                            </fieldset>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
