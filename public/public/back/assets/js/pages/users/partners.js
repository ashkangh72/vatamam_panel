$('#partner-accept-form').on('submit', function (event) {
    event.preventDefault();

    const id = $('#partner-accept-modal .modal-body').data('id');
    $('#partner-accept-modal').modal('hide');

    let formData = new FormData(this);
    formData.append('id', id);

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        success: function (data) {
            toastr.success('درخواست همکاری با موفقیت تایید شد.');

            location.reload();
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
$(document).on("click", '#accept-btn', function (event) {
    event.preventDefault();

    const id = $(this).data('id');

    $('#partner-accept-modal .modal-body').data('id', id);
    $('#partner-accept-modal').modal('show');
});

$('#partner-reject-form').on('submit', function (e) {
    e.preventDefault();

    const id = $('#partner-reject-modal .modal-body').data('id');

    $('#partner-reject-modal').modal('hide');

    let formData = new FormData(this);
    formData.append('id', id);

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        success: function (data) {
            toastr.success('درخواست همکاری با موفقیت رد شد.');

            location.reload();
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
$(document).on("click", '#reject-btn', function (event) {
    event.preventDefault();

    const id = $(this).data('id');

    $('#partner-reject-modal .modal-body').data('id', id);
    $('#partner-reject-modal').modal('show');
})
