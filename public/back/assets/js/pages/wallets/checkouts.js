$('#checkout-accept-form').on('submit', function (event) {
    event.preventDefault();

    const id = $('#checkout-accept-modal .modal-body').data('id');
    $('#checkout-accept-modal').modal('hide');

    let formData = new FormData(this);
    formData.append('id', id);

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        success: function (data) {
            if(data.success == 400){
                toastr.error(data.message);
            }else{
                toastr.success(data.message);

                location.reload();
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
});
$(document).on("click", '#accept-btn', function (event) {
    event.preventDefault();

    const id = $(this).data('id');

    $('#checkout-accept-modal .modal-body').data('id', id);
    $('#checkout-accept-modal').modal('show');
});

$('#checkout-reject-form').on('submit', function (e) {
    e.preventDefault();

    const id = $('#checkout-reject-modal .modal-body').data('id');

    $('#checkout-reject-modal').modal('hide');

    let formData = new FormData(this);
    formData.append('id', id);

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        success: function (data) {
            toastr.success('برداشت با موفقیت رد شد.');

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

    $('#checkout-reject-modal .modal-body').data('id', id);
    $('#checkout-reject-modal').modal('show');
})
