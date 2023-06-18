$(document).ready(function () {
    $(document).on('change', '#ftp-form input[data-class!=""]', function () {
        if ($(this).prop('checked')) {
            $('.' + $(this).data('class')).prop('disabled', false);
        } else {
            $('.' + $(this).data('class')).prop('disabled', true);
        }
    });

    $('#ftp-form input[data-class!=""]').trigger('change');

    jQuery('#ftp-form').validate();

    $('#ftp-form').submit(function (e) {
        e.preventDefault();

        if ($(this).valid()) {
            let formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                success: function (data) {
                    if (data == 'success') {
                        Swal.fire({
                            type: 'success',
                            title: 'تغییرات با موفقیت ذخیره شد',
                            confirmButtonClass: 'btn btn-primary',
                            confirmButtonText: 'باشه',
                            buttonsStyling: false,
                        });
                    }
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
});
