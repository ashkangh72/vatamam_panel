// validate form with jquery validation plugin
jQuery('#marketing-campaign-create-form').validate({
    rules: {
        'minimum_purchase': {
            required: true,
        },
        'products_commission_percent': {
            required: true,
        },
        'discounted_products_commission_percent': {
            required: true,
        },
        'discounted_orders_commission_percent': {
            required: true,
        },
        'start_at': {
            required: true,
        },
        'end_at': {
            required: true,
        },
    },
    messages: {
        'minimum_purchase': {
            required: 'لطفا حداقل سفارش را وارد کنید',
        },
        'products_commission_percent': {
            required: 'لطفا درصد کمیسیون محصولات را وارد کنید',
        },
        'discounted_products_commission_percent': {
            required: 'لطفا درصد کمیسیون محصولات تخفیف دار را وارد کنید',
        },
        'discounted_orders_commission_percent': {
            required: 'لطفا درصد کمیسیون سفارشات تخفیف دار را وارد کنید',
        },
        'start_at': {
            required: 'لطفا تاریخ شروع تعرفه را وارد کنید',
        },
        'end_at': {
            required: 'لطفا تاریخ پایان تعرفه را وارد کنید',
        },

    }
});

$('#marketing-campaign-create-form').submit(function(e) {
    e.preventDefault();

    if ($(this).valid() && !$(this).data('disabled')) {
        let formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function(data) {
                $('#marketing-campaign-create-form').data('disabled', true);
                location.href = $('#marketing-campaign-create-form').data('redirect');
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
    }
});

$("#start_at_picker").pDatepicker({
    timePicker: {
        enabled: true,
        meridian: {
            enabled: false,
        },
        second: {
            enabled: false,
        }
    },
    toolbox: {
        // enabled: true,
        calendarSwitch: {
            enabled: false,
        },
    },
    initialValue: false,
    altField: '#start_at',
    altFormat: 'YYYY-MM-DD H:mm:ss',

    onSelect: function (unixDate) {
        let date = $('#start_at');
        date.val((date.val()).toEnglishDigit());
    },
    onSet: function (unixDate) {
        let date = $('#start_at');
        date.val((date.val()).toEnglishDigit());
    },
});

$("#end_at_picker").pDatepicker({
    timePicker: {
        enabled: true,
        meridian: {
            enabled: false,
        },
        second: {
            enabled: false,
        }
    },
    toolbox: {
        // enabled: true,
        calendarSwitch: {
            enabled: false,
        },
    },
    initialValue: false,
    altField: '#end_at',
    altFormat: 'YYYY-MM-DD H:mm:ss',

    onSelect: function (unixDate) {
        let date = $('#end_at');
        date.val((date.val()).toEnglishDigit());
    },
    onSet: function (unixDate) {
        let date = $('#end_at');
        date.val((date.val()).toEnglishDigit());
    }
});
