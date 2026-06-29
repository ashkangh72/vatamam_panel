$(document).on("click", '#accept-btn', function (event) {
    event.preventDefault();
    const id = $(this).data('id');
    $('#expert-checkout-accept-modal .modal-body').data('id', id);
    $('#expert-checkout-accept-modal').modal('show');
});

$(document).on("click", '#reject-btn', function (event) {
    event.preventDefault();
    const id = $(this).data('id');
    $('#expert-checkout-reject-modal .modal-body').data('id', id);
    $('#expert-checkout-reject-modal').modal('show');
});

$('#expert-checkout-accept-form').on('submit', function (event) {
    event.preventDefault();
    const id = $('#expert-checkout-accept-modal .modal-body').data('id');
    let formData = new FormData(this);
    formData.append('id', id);

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function (xhr) {
            block('#main-card');
            xhr.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
        },
        success: function (data) {
            if (data.success === 400) {
                toastr.error(data.message);
            } else {
                toastr.success(data.message);
                location.reload();
            }
        },
        complete: function () {
            unblock('#main-card');
        }
    });
});

$('#expert-checkout-reject-form').on('submit', function (event) {
    event.preventDefault();
    const id = $('#expert-checkout-reject-modal .modal-body').data('id');
    let formData = new FormData(this);
    formData.append('id', id);

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function (xhr) {
            block('#main-card');
            xhr.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
        },
        success: function (data) {
            if (data.success === 400) {
                toastr.error(data.message);
            } else {
                toastr.success(data.message);
                location.reload();
            }
        },
        complete: function () {
            unblock('#main-card');
        }
    });
});
