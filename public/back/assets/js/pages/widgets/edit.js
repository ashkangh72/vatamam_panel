$(document).ready(function () {
    $('#widget-key').on('change', function () {
        $('#template .row').empty();

        let option = $(this).find(":selected");

        if (!option.val()) {
            return;
        }

        $.ajax({
            url: option.data('action'),
            type: 'GET',
            data: {
                option: option.val()
            },
            success: function (data) {
                $('#template .row').append(data)
            },
            beforeSend: function (xhr) {
                block('#widget-edit-form');
            },
            complete: function () {
                unblock('#widget-edit-form');
            },
        });
    });

    jQuery('#widget-edit-form').validate();

    $('#widget-edit-form').submit(function (e) {
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
});
