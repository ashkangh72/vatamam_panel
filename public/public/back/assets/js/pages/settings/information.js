$(document).ready(function() {

    /*=========+===================
      Information Tab Js Codes
    ===============================*/

    // validate form with jquery validation plugin
    jQuery('#information-form').validate({
        rules: {
            'info_site_title': {
                required: true,
            },

        },
        messages: {
            'info_site_title': {
                required: 'لطفا عنوان وبسایت را وارد کنید',
            },

        }
    });

    $('#information-form').submit(function(e) {
        e.preventDefault();

        if ($(this).valid()) {
            let formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                success: function(data) {
                    Swal.fire({
                        type: 'success',
                        title: 'تغییرات با موفقیت ذخیره شد',
                        confirmButtonClass: 'btn btn-primary',
                        confirmButtonText: 'باشه',
                        buttonsStyling: false,
                    });
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

});

let amountInput = $('.amount-input');
amountInput.attr('autocomplete', 'off');
$(document).on('keyup', '.amount-input', function() {
    if (!$(this).next('.form-text').length) {
        $(this).after('<small class="form-text text-success amount-helper"></small>');
    }
    let text = number_format($(this).val()) + ' تومان';
    $(this).next('.form-text').text(text);
});
amountInput.trigger('keyup');
