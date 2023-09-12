'use strict';
// Class definition

let datatable;

let order_datatable = (function() {
    // Private functions

    let server_data;

    let options = {
        // datasource definition
        data: {
            type: 'remote',
            source: {
                read: {
                    url: $('#orders_datatable').data('action'),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    map: function(raw) {
                        // sample data mapping
                        let dataSet = raw;
                        server_data = raw;

                        if (typeof raw.data !== 'undefined') {
                            dataSet = raw.data;
                        }

                        return dataSet;
                    },
                    params: {
                        query: $('#filter-orders-form').serializeJSON(),
                    },
                },
            },
            pageSize: 10,
            serverPaging: true,
            serverFiltering: true,
            serverSorting: true,
        },

        layout: {
            scroll: true,
        },

        rows: {
            autoHide: false,
        },

        // columns definition
        columns: [{
                field: 'id',
                title: '#',
                sortable: false,
                width: 20,
                selector: {
                    class: '',
                },
                textAlign: 'center',
            },
            {
                field: 'ordering',
                sortable: false,
                title: 'ردیف',
                width: 40,
                template: function(row, i) {
                    return parseInt(server_data.meta.perpage * (server_data.meta.current_page - 1)) + parseInt(i) + 1;
                },
            },
            {
                field: 'name',
                title: 'نام سفارش دهنده',
                template: function(row) {
                    return '<a href="' + row.user_profile + '">' + row.user.name + '</a>';
                },
            },
            {
                field: 'seller',
                title: 'نام فروشنده',
                template: function(row) {
                    return '<a href="' + row.seller_profile + '">' + row.seller.name + '</a>';
                }
            },
            {
                field: 'order_id',
                title: 'شماره سفارش',
                width: 40,
            },
            {
                field: 'created_at',
                sortable: 'desc',
                title: 'تاریخ ثبت سفارش',
                template: function(row) {
                    return '<span class="ltr">' + row.created_at + '</span>';
                },
            },
            {
                field: 'price',
                title: 'قیمت کل',
            },
            {
                field: 'status',
                title: 'وضعیت',
                width: 100,
                textAlign: 'center',
                // callback function support for column rendering
                template: function(row) {
                    if (row.is_refunded) {
                        let status = {
                            pending: {
                                title: 'منتظر بررسی',
                                class: ' badge-info',
                            },
                            accepted: {
                                title: 'تایید شده',
                                class: ' badge-success',
                            },
                            rejected: {
                                title: 'رد شده',
                                class: ' badge-danger',
                            }
                        };
                        return '<div class="badge badge-pill badge-danger badge-md">دارای مرجوعی</div>' +
                            '<div class="badge badge-pill ' + status[row.refund_status].class + ' badge-md">' + status[row.refund_status].title + '</div>';
                    }
                    let status = {
                        pending: {
                            title: 'جدید',
                            class: ' badge-warning',
                        },
                        locked: {
                            title: 'بسته شده',
                            class: ' badge-danger',
                        },
                        paid: {
                            title: 'پرداخت شده',
                            class: ' badge-success',
                        },
                        canceled: {
                            title: 'لغو شده',
                            class: ' badge-danger',
                        }
                    };
                    return '<div class="badge badge-pill ' + status[row.status].class + ' badge-md">' + status[row.status].title + '</div>';
                },
            },
            {
                field: 'shipping_status',
                title: 'وضعیت ارسال',
                width: 100,
                textAlign: 'center',
                // callback function support for column rendering
                template: function(row) {
                    let shipping_status = {
                        pending: {
                            title: 'درحال بررسی',
                            class: ' badge-success',
                        },
                        shipping_request: {
                            title: 'منتظر ارسال',
                            class: ' badge-info',
                        },
                        shipped: {
                            title: 'ارسال شده',
                            class: ' badge-purple',
                        }
                    };

                    if (row.status === 'unpaid' || row.status === 'canceled' || row.shipping_status === null) {
                        return '<div></div>';
                    }
                    return '<div class="badge badge-pill ' + shipping_status[row.shipping_status].class + ' badge-md">' + shipping_status[row.shipping_status].title + '</div>';
                },
            },
            {
                field: 'actions',
                title: 'عملیات',
                sortable: false,
                width: 125,
                overflow: 'visible',
                autoHide: false,
                template: function(row) {
                    let actions = '<div class="btn-group-vertical"><a href="' + row.links.view + '" class="btn btn-outline-info waves-effect waves-light">مشاهده</a>';
                    if (row.is_refunded && (row.refund_status == 'rejected' || row.refund_status == 'pending'))
                        actions += '<button data-action="' + row.links.accept + '" class="btn btn-outline-success btn-accept">تایید برگشت</button>';
                    if (row.is_refunded && (row.refund_status == 'accepted' || row.refund_status == 'pending'))
                        actions += '<button data-action="' + row.links.reject + '" class="btn btn-outline-danger btn-reject">رد برگشت</button>';

                    return actions+'</div>';
                },
            },
        ],
    };

    let initDatatable = function() {
        // enable extension
        options.extensions = {
            // boolean or object (extension options)
            checkbox: true,
        };

        datatable = $('#orders_datatable').KTDatatable(options);

        $('#filter-orders-btn').on('click', function(event) {
            event.preventDefault();
            formDataToUrl('filter-orders-form');
            datatable.setDataSourceQuery($('#filter-orders-form').serializeJSON());
            datatable.reload();
        });

        datatable.on('datatable-on-click-checkbox', function(e) {
            let ids = datatable.checkbox().getSelectedId();
            let count = ids.length;

            $('#datatable-selected-rows').html(count);

            if (count > 0) {
                $('.datatable-actions').collapse('show');
            } else {
                $('.datatable-actions').collapse('hide');
            }
        });

        datatable.on('datatable-on-reloaded', function(e) {
            $('.datatable-actions').collapse('hide');
        });
    };

    return {
        // public functions
        init: function() {
            initDatatable();
        },
    };
})();

jQuery(document).ready(function() {
    order_datatable.init();
});

$(document).on('click', '.btn-accept', function(e) {
    e.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
        title: 'آیا میخواهید بازگردانی را تایید کنید ؟',
        text: "",
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'تایید شود',
        cancelButtonText: 'خیر',
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: 'POST',
                url: $(this).data('action'),
                data: {},
                success: function (data) {
                    $(".modal").modal('hide');
                    toastr.success('با موفقیت تایید شد.');

                    window.location.reload();
                },
                beforeSend: function(xhr) {
                    block('#main-card');
                    xhr.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                },
                complete: function() {
                    unblock('#main-card');
                },
                cache: false,
                contentType: false,
                processData: false
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire(
                'مشکلی پیش آمد دوباره تلاش کنید !',
                '',
                'error'
            )
        }
    })
});

$(document).on('click', '.btn-reject', function(e) {
    e.preventDefault();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
        title: 'آیا میخواهید درخواست بازگردانی را رد کنید ؟',
        text: "",
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'رد شود',
        cancelButtonText: 'خیر',
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: 'POST',
                url: $(this).data('action'),
                data: {},
                success: function (data) {
                    $(".modal").modal('hide');
                    toastr.success('با موفقیت رد شد.');

                    window.location.reload();
                },
                beforeSend: function(xhr) {
                    block('#main-card');
                    xhr.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                },
                complete: function() {
                    unblock('#main-card');
                },
                cache: false,
                contentType: false,
                processData: false
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire(
                'مشکلی پیش آمد دوباره تلاش کنید !',
                '',
                'error'
            )
        }
    })
});
