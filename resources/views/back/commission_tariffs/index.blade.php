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
                                    <li class="breadcrumb-item">مدیریت تعرفه های کمیسیون
                                    </li>
                                    <li class="breadcrumb-item active">لیست تعرفه ها
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-header-right text-md-right col-md-3 col-12 d-md-block d-none">
                    <div class="form-group breadcrum-right">
                        <div id="save-changes" class="spinner-border text-success" role="status" style="display: none">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body" id="main-card">
                @if($commissionTariffs->count())
                    <section class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <thead>
                                        <tr>
                                            <th>حداقل</th>
                                            <th>حداکثر</th>
                                            <th>درصد کمیسیون</th>
                                            <th class="text-center">عملیات</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($commissionTariffs as $commissionTariff)
                                                <tr id="commission-tariff-{{ $commissionTariff->id }}">
                                                    <td>{{ number_format($commissionTariff->min) }} تومان</td>
                                                    <td>{{ number_format($commissionTariff->max) }} تومان</td>
                                                    <td>{{ $commissionTariff->commission_percent }} درصد</td>

                                                    <td class="text-center">

                                                        @can('commission_tariffs.update')
                                                            <a href="{{ route('admin.commission_tariffs.edit', ['commission_tariff' => $commissionTariff]) }}" class="btn btn-info mr-1 waves-effect waves-light">ویرایش</a>
                                                        @endcan

                                                        @can('commission_tariffs.delete')
                                                            <button type="button" data-commission-tariff="{{ $commissionTariff->id }}" class="btn btn-danger mr-1 waves-effect waves-light btn-delete"  data-toggle="modal" data-target="#delete-modal">حذف</button>
                                                        @endcan

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

    {{-- delete link modal --}}
    <div class="modal fade text-left" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel19" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel19">آیا مطمئن هستید؟</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    با حذف تعرفه دیگر قادر به بازیابی آن نخواهید بود
                </div>
                <div class="modal-footer">
                    <form action="#" id="commission-tariff-delete-form">
                        @csrf
                        @method('delete')
                        <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal">خیر</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light">بله حذف شود</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Page Js codes -->
    <script src="{{ asset('public/back/assets/js/pages/commission-tariffs/index.js') }}"></script>
@endpush
