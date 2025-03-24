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
                                    <li class="breadcrumb-item active">مدیریت تغییر مسیرها
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Add new redirect -->
                <section class="card">
                    <div class="card-header">
                        <h4 class="card-title">افزودن تغییر مسیر جدید</h4>
                    </div>

                    <div class="card-content" id="form-card">
                        <div class="card-body">
                            <div class="col-12 col-md-10 offset-md-1">
                                <form class="form" id="redirect-create-form"
                                    action="{{ route('admin.redirects.store') }}" method="post">
                                    @csrf
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="old_url">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            نشانی قدیمی
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="new_url">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            نشانی جدید
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="input-group">
                                                    <select class="form-control" name="http_code">
                                                        @foreach([
                                                            301 => "HTTP/1.0/Permanent",
                                                            302 => "HTTP/1.0/Temporary",
                                                            303 => "HTTP/1.1/Temporary",
                                                            307 => "HTTP/1.1/Temporary",
                                                            308 => "HTTP/1.1/Permanent",
                                                            410 => "HTTP/Gone/Permanent"
                                                            ] as $httpRedirectCode => $type)
                                                            <option value="{{ $httpRedirectCode }}">{{ $httpRedirectCode .' - '. $type }}</option>
                                                        @endforeach
                                                    </select>

                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            HTTP Code
                                                        </div>
                                                    </div>
                                                </div>
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
                <!--/ Add redirect -->

                <div id="main-content-div">
                    @if ($redirects->count())
                        <section class="card">
                            <div class="card-header">
                                <h4 class="card-title">لیست تغییر مسیرها</h4>
                            </div>
                            <div class="card-content" id="main-card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th class="text-center">HTTP Code</th>
                                                    <th class="text-center">نشانی قدیمی</th>
                                                    <th class="text-center">نشانی جدید</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($redirects as $redirect)
                                                    <tr id="redirect-{{ $redirect->id }}-tr">
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td class="text-center">{{ $redirect->http_code }}</td>
                                                        <td class="text-center">{{ urldecode($redirect->old_url) }}</td>
                                                        <td class="text-center">{{ urldecode($redirect->new_url) }}</td>
                                                        <td class="text-center">
                                                            <button type="button" data-id="{{ $redirect->id }}"
                                                                class="btn btn-danger mr-1 waves-effect waves-light btn-delete"
                                                                data-toggle="modal" data-target="#delete-modal">حذف</button>
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
                                <h4 class="card-title">لیست تغییر مسیرها</h4>
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

    {{-- delete redirect modal --}}
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
                <div class="modal-body">با حذف این مورد تغییراتی در مسیردهی ایجاد می شود.</div>
                <div class="modal-footer">
                    <form action="#" id="redirect-delete-form">
                        @csrf
                        @method('delete')
                        <button type="button" class="btn btn-success waves-effect waves-light"
                            data-dismiss="modal">خیر</button>
                        <button type="submit" class="btn btn-danger waves-effect waves-light">بله حذف شود</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('public/back/assets/js/pages/redirects/index.js') }}"></script>
@endpush
