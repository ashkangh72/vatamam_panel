$(document).on('click', '.btn-delete', function () {
    $('#size-type-delete-form').attr('action', $(this).data('action'));
    $('#size-type-delete-form').data('id', $(this).data('id'));
});

$('#size-type-delete-form').submit(function (e) {
    e.preventDefault();

    $('#delete-modal').modal('hide');

    let formData = new FormData(this);

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        success: function (data) {
            //get current url
            let url = window.location.href;

            //remove size-type tr
            $('#size-type-' + $('#size-type-delete-form').data('id') + '-tr').remove();

            toastr.success('راهنمای سایز با موفقیت حذف شد.');

            //refresh size-types list
            $('.app-content').load(url + ' .app-content > *');
        },
        beforeSend: function (xhr) {
            block('#main-card');
            xhr.setRequestHeader(
                'X-CSRF-TOKEN',
                $('meta[name="csrf-token"]').attr('content')
            );
        },
        complete: function () {
            unblock('#main-card');
        },
        cache: false,
        contentType: false,
        processData: false
    });
});
