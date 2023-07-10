$('#discount-type').on('change', function() {
    switch ($(this).val()) {
        case 'percent':
            {
                $('.amount').hide();
                $('.percent').show();
                break;
            }
        case 'amount':
            {
                $('.amount').show();
                $('.percent').hide();
                break;
            }
    }
});

$('#discount-type').trigger('change');

$('#users-include').select2({
    rtl: true,
    width: '100%',
});

var startDatePicker = $('#start_date_picker').pDatepicker({
    timePicker: {
        enabled: true,
        meridian: {
            enabled: false,
        },
        second: {
            enabled: false,
        },
    },
    toolbox: {
        // enabled: true,
        calendarSwitch: {
            enabled: false,
        },
    },
    initialValue: false,
    altField: '#start_date',
    altFormat: 'YYYY-MM-DD H:mm:ss',

    onSelect: function(unixDate) {
        var date = $('#start_date').val();
        $('#start_date').val(date.toEnglishDigit());
    },
    onSet: function(unixDate) {
        var date = $('#start_date').val();
        $('#start_date').val(date.toEnglishDigit());
    },
});

var start_date = $('#start_date_picker').val();

if (start_date) {
    startDatePicker.setDate(parseInt(start_date + '000'));
}

$('#start_date_picker').on('keydown', function(e) {
    e.preventDefault();
    $(this).val('');
    $('#start_date').val('');
});

var endDatePicker = $('#end_date_picker').pDatepicker({
    timePicker: {
        enabled: true,
        meridian: {
            enabled: false,
        },
        second: {
            enabled: false,
        },
    },
    toolbox: {
        // enabled: true,
        calendarSwitch: {
            enabled: false,
        },
    },
    initialValue: false,
    altField: '#end_date',
    altFormat: 'YYYY-MM-DD H:mm:ss',

    onSelect: function(unixDate) {
        var date = $('#end_date').val();
        $('#end_date').val(date.toEnglishDigit());
    },
    onSet: function(unixDate) {
        var date = $('#end_date').val();
        $('#end_date').val(date.toEnglishDigit());
    },
});

var end_date = $('#end_date_picker').val();

if (end_date) {
    endDatePicker.setDate(parseInt(end_date + '000'));
}

$('#end_date_picker').on('keydown', function(e) {
    e.preventDefault();
    $(this).val('');
    $('#end_date').val('');
});

//--------------- generate random code
$('#generate-new-code').on('click', function() {
    var code = randomString(6);
    $('#main-card input[name="code"]').val(code);
});
