@extends('back.layouts.master')

@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <div class="breadcrumb-wrapper col-12">
                                <ol class="breadcrumb no-border">
                                    <li class="breadcrumb-item">مدیریت
                                    </li>
                                    <li class="breadcrumb-item">مدیریت محصولات
                                    </li>
                                    <li class="breadcrumb-item active">قوانین موتورهای جستجو
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Add new search engine rule -->
                <section class="card">
                    <div class="card-header">
                        <h4 class="card-title">افزودن قانون جدید</h4>
                    </div>

                    <div class="card-content" id="form-card">
                        <div class="card-body">
                            <div class="col-12 col-md-10 offset-md-1">
                                <form class="form" id="search_engine_rule-create-form"
                                      action="{{ route('admin.search-engine-rules.store') }}" method="post">
                                    @csrf
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-8 col-12">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="slug">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            {{ env('WEBSITE_URL') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mt-2">
                                                <fieldset class="checkbox">
                                                    <div class="vs-checkbox-con vs-checkbox-primary">
                                                        <input type="checkbox" name="index" checked disabled>
                                                        <span class="vs-checkbox">
                                                            <span class="vs-checkbox--check">
                                                                <i class="vs-icon feather icon-check"></i>
                                                            </span>
                                                        </span>
                                                        <span>noindex, nofollow</span>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12 text-right">
                                                <button type="submit"
                                                        class="btn btn-primary mb-1 waves-effect waves-light">افزودن
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
                <!--/ Add city -->

                <div id="main-content-div">
                    @if ($searchEngineRules->count())
                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">لیست قوانین</h4>
                            </div>
                            <div class="card-content" id="main-card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped mb-0">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th class="text-center">لینک</th>
                                                <th class="text-center">noindex, nofollow</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($searchEngineRules as $searchEngineRule)
                                                <tr id="search_engine_rule-{{ $searchEngineRule->id }}-tr">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td class="text-center">{{ $searchEngineRule->slug }}</td>
                                                    <td class="text-center">
                                                        @if(!$searchEngineRule->index)
                                                            بله
                                                        @else
                                                            خیر
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" data-id="{{ $searchEngineRule->id }}"
                                                                class="btn btn-danger mr-1 waves-effect waves-light btn-delete"
                                                                data-toggle="modal" data-target="#delete-modal">حذف
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </section>

                    @else
                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">لیست قوانین</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="card-text">
                                        <p>چیزی برای نمایش وجود ندارد!</p>
                                    </div>
                                </div>
                            </div>
                        </section>
                    @endif
                </div>

            </div>
        </div>
    </div>

    {{-- delete shipping_cost modal --}}
    <div class="modal fade text-left" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel19"
         style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel19">آیا مطمئن هستید؟</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">با حذف این مورد تغییراتی در robots.txt ایجاد می شود.</div>
                <div class="modal-footer">
                    <form action="#" id="search_engine_rule-delete-form">
                        @csrf
                        @method('delete')
                        <button type="button" class="btn btn-success waves-effect waves-light"
                                data-dismiss="modal">خیر
                        </button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light">بله حذف شود</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('public/back/assets/js/pages/search-engine-rules/index.js') }}"></script>
@endpush
