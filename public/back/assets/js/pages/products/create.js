Dropzone.autoDiscover = false;

$('#product-create-form').submit(function (e) {
    e.preventDefault();

    if ($(this).valid() && !$(this).data('disabled')) {

        var date = $('#publish_date').val();
        $('#publish_date').val(date.toEnglishDigit());

        var formData = new FormData(this);
        formData.append('description', CKEDITOR.instances['description'].getData())

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function (data) {
                $('#product-create-form').data('disabled', true);
                window.location.href = BASE_URL + "/products";
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
    }

});

$("#publish_date_picker").pDatepicker({
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
    altField: '#publish_date',
    altFormat: 'YYYY-MM-DD H:mm:ss',

    onSelect: function (unixDate) {
        var date = $('#publish_date').val();
        $('#publish_date').val(date.toEnglishDigit());
    }
});
$(document).on('load change click', '.publish_date_picker_discount', function (e) {
    $(this).pDatepicker({
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
        altField: e.target.nextElementSibling,
        altFormat: 'YYYY-MM-DD H:mm:ss',

        onSelect: function (unixDate) {
            var date = $(e.target.nextElementSibling).val();
            $(e.target.nextElementSibling).val(date.toEnglishDigit());
        }
    });

});
