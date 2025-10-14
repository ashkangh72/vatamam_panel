// validate form with jquery validation plugin
jQuery('#commission-tariff-create-form').validate({
    rules: {
        'min': {
            required: true,
        },
        'commission_percent': {
            required: true,
        }
    },
});

$('#commission-tariff-create-form').submit(function(e) {
    e.preventDefault();

    if ($(this).valid() && !$(this).data('disabled')) {
        var formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function(data) {
                $('#commission-tariff-create-form').data('disabled', true);
                window.location.href = BASE_URL + "/commission_tariffs";
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

$('.min-input').attr('autocomplete', 'off');
$(document).on('keyup', '.min-input', function() {
    if (!$(this).val()) {
        $(this).next('.form-text').remove();
        return;
    }

    if (!$(this).next('.form-text').length) {
        $(this).after('<small class="form-text text-success amount-helper"></small>');
    }

    var text = number_format($(this).val()) + ' تومان';

    $(this).next('.form-text').text(text);
});

$('.min-input').trigger('keyup');

$('.max-input').attr('autocomplete', 'off');
$(document).on('keyup', '.max-input', function() {
    if (!$(this).val()) {
        $(this).next('.form-text').remove();
        return;
    }

    if (!$(this).next('.form-text').length) {
        $(this).after('<small class="form-text text-success amount-helper"></small>');
    }

    var text = number_format($(this).val()) + ' تومان';

    $(this).next('.form-text').text(text);
});

$('.max-input').trigger('keyup');

$('.commission-percent-input').attr('autocomplete', 'off');
$(document).on('keyup', '.commission-percent-input', function() {
    if (!$(this).val()) {
        $(this).next('.form-text').remove();
        return;
    }

    if (!$(this).next('.form-text').length) {
        $(this).after('<small class="form-text text-success amount-helper"></small>');
    }

    var text = number_format($(this).val()) + ' درصد';

    $(this).next('.form-text').text(text);
});

$('.commission-percent-input').trigger('keyup');
