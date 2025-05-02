"use strict";
// Class definition

let datatable;

let auction_datatable = function () {

    // Private functions

    let options = {
        // datasource definition
        data: {
            type: 'remote',
            source: {
                read: {
                    url: $('#auctions_datatable').data('action'),
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    map: function (raw) {

                        // sample data mapping
                        let dataSet = raw;
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
            field: 'username',
            title: 'نام کاربر',
            width: 150,
            template: function (row) {
                return row.user.name;
            }
        },
        {
            field: 'amount',
            title: 'قیمت',
            width: 150,
            template: function (row) {
                return row.amount;
            }
        },
        {
            field: 'created_at',
            width: 300,
            sortable: 'desc',
            title: 'تاریخ ثبت',
            template: function (row) {
                return '<span class="ltr">' + row.created_at + '</span>';
            }
        },
        ],
    };

    let initDatatable = function () {
        // enable extension
        options.extensions = {
            // boolean or object (extension options)
            checkbox: true,
        };

        datatable = $('#auctions_datatable').KTDatatable(options);

        $('#filter-auction-btn').on('click', function (event) {
            event.preventDefault();
            formDataToUrl('filter-auctions-form');
            datatable.setDataSourceQuery($('#filter-auctions-form').serializeJSON());
            datatable.reload();
        });

        datatable.on('datatable-on-click-checkbox',
            function (e) {
                let ids = datatable.checkbox().getSelectedId();
                let count = ids.length;

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
    auction_datatable.init();
});

