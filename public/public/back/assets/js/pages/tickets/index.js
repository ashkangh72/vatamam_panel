$(document).on('click', '.btn-delete', function () {
    $('#comment-delete-form').attr('action', $(this).data('action'));
    $('#comment-delete-form').data('id', $(this).data('comment'));
});
$('#comment-delete-form').submit(function (e) {
    alert('asas');
    e.preventDefault();

    $('#delete-modal').modal('hide');

    let formData = new FormData(this);

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        success: function (data) {

            //remove comment tr
            $('#comment-' + $('#comment-delete-form').data('id') + '-tr').remove();

            toastr.success('تیکت با موفقیت حذف شد.');

            reloadDiv('.list-comments');
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
});


$(document).on('click', '.btn-close', function () {
    $('#ticket-close-form').attr('action', $(this).data('action'));
    $('#ticket-close-form').data('id', $(this).data('comment'));
});
$('#ticket-close-form').submit(function (e) {
    e.preventDefault();

    $('#close-modal').modal('hide');

    let formData = new FormData(this);

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        success: function (data) {

            toastr.success('تیکت با موفقیت بسته شد.');

            reloadDiv('.list-comments');
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
});


$(document).on('click', '.reply-ticket', function () {
    $('#show-modal').modal('show');
});


$(document).on('submit', '#ticket-reply-form', function (e) {
    e.preventDefault();

    let form = $(this);
    let formData = new FormData(this);

    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: formData,
        success: function (data) {
            reloadDiv('.list-comments');

            $('#show-modal').modal('hide');

            toastr.success("تغییرات با موفقیت انجام شد");
        },
        beforeSend: function (xhr) {
            block('.comment-show-modal');
            xhr.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
        },
        complete: function () {
            unblock('.comment-show-modal');
        },
        cache: false,
        contentType: false,
        processData: false
    });
});