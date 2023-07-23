CKEDITOR.replace('content');

jQuery('#page-create-form').validate({

    rules: {
        'title': {
            required: true,
        },

    },
    messages: {
        'title': {
            required: 'لطفا عنوان صفحه را وارد کنید',
        },

    }
});

$('#page-create-form').submit(function(e) {
    e.preventDefault();

    if ($(this).valid() && !$(this).data('disabled')) {
        let formData = new FormData(this);
        formData.append('content', CKEDITOR.instances['content'].getData())

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function(data) {
                $('#page-create-form').data('disabled', true);
                window.location.href = BASE_URL + "/pages";
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
