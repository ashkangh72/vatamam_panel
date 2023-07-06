"use strict";
// Class definition

var datatable;

var auction_datatable = function() {

    // Private functions

    var options = {
        // datasource definition
        data: {
            type: 'remote',
            source: {
                read: {
                    url: $('#auctions_datatable').data('action'),
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    map: function(raw) {

                        // sample data mapping
                        var dataSet = raw;
                        if (typeof raw.data !== 'undefined') {
                            dataSet = raw.data;
                        }
                        return dataSet;
                    },
                    params: {
                        query: $('#filter-auctions-form').serializeJSON()
                    }
                },
            },
            pageSize: 10,
            serverPaging: true,
            serverFiltering: true,
            serverSorting: true,
        },

        layout: {
            scroll: true
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
                    class: ''
                },
                textAlign: 'center',
            },
            {
                field: 'picture',
                title: 'تصویر',
                sortable: false,
                width: 80,
                template: function(row) {

                    return '<img class="post-thumb" src="' + row.picture + '" alt="' + row.title + '">';
                }
            },
            {
                field: 'title',
                title: 'عنوان',
                width: 200,
                template: function(row) {
                    return row.title;
                }
            },
            {
                field: 'sku',
                title: 'شناسه مزایده',
                width: 200,
                template: function(row) {
                    return row.sku;
                }
            },
            {
                field: 'base_price',
                title: 'قیمت پایه',
                width: 200,
                template: function(row) {
                    return row.base_price;
                }
            },
            {
                field: 'username',
                title: 'نام کاربر',
                width: 200,
                template: function(row) {
                    return row.username;
                }
            },
            {
                field: 'category',
                title: 'دسته',
                width: 200,
                template: function(row) {
                    return row.category;
                }
            },
            {
                field: 'status',
                width: 200,
                sortable: 'desc',
                title: 'وضعیت',
                template: function(row) {
                    let status = ``;
                    if (row.status==='rejected')
                        status=`<span class="badg badge-danger mb-2">رد شده</span><small>${row.reject_reason}</small>`;
                    if (row.status==='approved')
                        status=`<span class="badge badge-success">تایید شده</span>`;
                    if (row.status==='pending_approval')
                        status=`<span class="badge badge-info">منتظر تایید</span>`;
                    return status;
                }
            },
            {
                field: 'created_at',
                sortable: 'desc',
                title: 'تاریخ ثبت',
                template: function(row) {
                    return '<span class="ltr">' + row.created_at + '</span>';
                }
            },
            {
                field: 'actions',
                title: 'عملیات',
                textAlign: 'center',
                sortable: false,
                width: 200,
                overflow: 'visible',
                autoHide: false,
                template: function(row) {

                    let btn=``;
                    if (row.status==='approved'){
                        btn=`<a data-id="${row.id}" id="reject-btn" href ="${row.links.reject}"class="btn btn-danger waves-effect waves-light">رد</a>`
                    }else{
                        btn=`<a data-id="${row.id}" id="accept-btn" href ="${row.links.accept}"class="btn btn-success waves-effect waves-light">تایید</a>
                                `
                    }
                    return btn;
                },
            },
        ],
    };

    var initDatatable = function() {
        // enable extension
        options.extensions = {
            // boolean or object (extension options)
            checkbox: true,
        };

        datatable = $('#auctions_datatable').KTDatatable(options);

        $('#filter-auction-btn').on('click', function(event) {
            event.preventDefault();
            formDataToUrl('filter-auctions-form');
            datatable.setDataSourceQuery($('#filter-auctions-form').serializeJSON());
            datatable.reload();
        });

        datatable.on('datatable-on-click-checkbox',
            function(e) {
                var ids = datatable.checkbox().getSelectedId();
                var count = ids.length;

                $('#datatable-selected-rows').html(count);

                if (count > 0) {
                    $('.datatable-actions').collapse('show');
                } else {
                    $('.datatable-actions').collapse('hide');
                }
            }
        );

        datatable.on('datatable-on-reloaded',
            function(e) {
                $('.datatable-actions').collapse('hide');
            }
        );
    };

    return {
        // public functions
        init: function() {
            initDatatable();
        },
    };
}();

jQuery(document).ready(function() {
    auction_datatable.init();
});

$('#auction-multiple-delete-form').on('submit', function(e) {
    e.preventDefault();

    $('#multiple-delete-modal').modal('hide');

    var formData = new FormData(this);
    var ids = datatable.checkbox().getSelectedId();

    ids.forEach(function(id) {
        formData.append('ids[]', id);
    });

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        success: function(data) {
            toastr.success('کاربران انتخاب شده با موفقیت حذف شدند.');
            datatable.reload();
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

});
$('#auction-accept-form').on('submit', function(e) {
    e.preventDefault();

    const id=$('#auction-accept-modal .modal-body').data('id');
    $('#auction-accept-modal').modal('hide');
    var formData = new FormData(this);
    formData.append('id',id);

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        success: function(data) {
            toastr.success('مزایده با موفقیت تایید شد.');
            datatable.reload();
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

});
$(document).on("click",'#accept-btn',function (event) {
    event.preventDefault();
    const id=$(this).data('id');
    $('#auction-accept-modal .modal-body').data('id',id);
    $('#auction-accept-modal').modal('show');

});

$('#auction-reject-form').on('submit', function(e) {
    e.preventDefault();

    const id=$('#auction-reject-modal .modal-body').data('id');
    const reason=$('#auction-reject-modal .modal-body input').val();

    $('#auction-reject-modal').modal('hide');
    var formData = new FormData(this);
    formData.append('id',id);
    formData.append('reason',reason);

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        success: function(data) {
            toastr.success('مزایده با موفقیت رد شد.');
            datatable.reload();
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

});
$(document).on("click",'#reject-btn',function (event) {
    event.preventDefault();
    const id=$(this).data('id');

    $('#auction-reject-modal .modal-body').data('id',id);
    $('#auction-reject-modal').modal('show');

})
