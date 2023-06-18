// validate form with jquery validation plugin
jQuery('#faq-create-form').validate({

    rules: {
        'question': {
            required: true,
        },
        'answer': {
            required: true,
        },
    },
});

$('#faq-create-form').submit(function (e) {
    e.preventDefault();
    let form = $(this);

    if ($(this).valid() && !$(this).data('disabled')) {
        let formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function (data) {
                if (data === 'success') {
                    form.data('disabled', true);
                    window.location.href = form.data('redirect');
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
