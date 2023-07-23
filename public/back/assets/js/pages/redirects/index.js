$(document).on('click', '.btn-delete', function() {
    $('#redirect-delete-form').attr('action', BASE_URL + '/redirects/' + $(this).data('id'));
    $('#redirect-delete-form').data('id', $(this).data('id'));
});

$('#redirect-delete-form').submit(function(e) {
    e.preventDefault();

    $('#delete-modal').modal('hide');

    let form = this;

    let formData = new FormData(this);

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        success: function(data) {
            //get current url
            let url = window.location.href;

            //remove redirect tr
            $('#redirect-' + $(form).data('id') + '-tr').remove();

            toastr.success('تغییر مسیر با موفقیت حذف شد.');

            //refresh search_engine_rule list
            $("#main-content-div").load(url + " #main-content-div > *");
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
});

$('#redirect-create-form').submit(function(e) {
    e.preventDefault();

    let form = $(this);
    let formData = new FormData(this);

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        success: function(data) {
            //get current url
            let url = window.location.href;

            toastr.success('تغییر مسیر با موفقیت ایجاد شد.');

            //refresh redirects list
            $("#main-content-div").load(url + " #main-content-div > *");

            form.trigger('reset');

        },
        beforeSend: function(xhr) {
            block('#form-card');
            xhr.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
        },
        complete: function() {
            unblock('#form-card');
        },
        cache: false,
        contentType: false,
        processData: false
    });

});
