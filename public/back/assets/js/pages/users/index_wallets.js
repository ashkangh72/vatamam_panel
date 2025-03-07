"use strict";
// Class definition

var datatable;

var user_datatable = function () {
    // Private functions

    var options = {
        // datasource definition
        data: {
            type: 'remote',
            source: {
                read: {
                    url: $('#users_datatable').data('action'),
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    map: function (raw) {
                        // sample data mapping
                        var dataSet = raw;
                        if (typeof raw.data !== 'undefined') {
                            dataSet = raw.data;
                        }
                        return dataSet;
                    },
                    params: {
                        query: $('#filter-users-form').serializeJSON()
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
        columns: [
        {
            field: 'fullname',
            title: 'نام',
            width: 200,
            template: function (row) {
                return row.name;
            }
        },
        {
            field: 'phone',
            title: 'شماره کاربر',
            width: 150,
            template: function (row) {
                return row.phone;
            }
        },
        {
            field: 'username',
            title: 'نام کاربری',
            width: 150,
            template: function (row) {
                return row.username;
            }
        },
        {
            field: 'national_id',
            title: 'کدملی',
            width: 150,
            template: function (row) {
                return row.national_id;
            }
        },
        {
            field: 'money',
            title: 'موجودی کیف پول',
            width: 200,
            template: function (row) {
                return row.money;
            }
        },
        {
            field: 'actions',
            title: 'عملیات',
            textAlign: 'center',
            sortable: false,
            width: 100,
            overflow: 'visible',
            autoHide: false,
            template: function (row) {
                    return '<a href="' + row.links.show + '" class="btn btn-info waves-effect waves-light">مشاهده</a>';
            },
        },
        ],
    };

    var initDatatable = function () {
        // enable extension
        options.extensions = {
            // boolean or object (extension options)
            checkbox: true,
        };

        datatable = $('#users_datatable').KTDatatable(options);

        $('#filter-users-btn').on('click', function (event) {
            event.preventDefault();
            formDataToUrl('filter-users-form');
            datatable.setDataSourceQuery($('#filter-users-form').serializeJSON());
            datatable.reload();
        });

        datatable.on('datatable-on-click-checkbox',
            function (e) {
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
            function (e) {
                $('.datatable-actions').collapse('hide');
            }
        );
    };

    return {
        // public functions
        init: function () {
            initDatatable();
        },
    };
}();

jQuery(document).ready(function () {
    user_datatable.init();
});

$('#user-multiple-delete-form').on('submit', function (e) {
    e.preventDefault();

    $('#multiple-delete-modal').modal('hide');

    var formData = new FormData(this);
    var ids = datatable.checkbox().getSelectedId();

    ids.forEach(function (id) {
        formData.append('ids[]', id);
    });

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        success: function (data) {
            toastr.success('کاربران انتخاب شده با موفقیت حذف شدند.');
            datatable.reload();
        },
        beforeSend: function (xhr) {
            block('#main-card');
            xhr.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
        },
        complete: function () {
            unblock('#main-card');
        },
        cache: false,
        contentType: false,
        processData: false
    });
});
