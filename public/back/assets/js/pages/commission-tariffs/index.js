$(document).on('click', '.btn-delete', function() {
    $('#commission-tariff-delete-form').attr('action', BASE_URL + '/commission_tariffs/' + $(this).data('commission-tariff'));
    $('#commission-tariff-delete-form').data('id', $(this).data('commission-tariff'));
});

$('#commission-tariff-delete-form').submit(function(e) {
    e.preventDefault();

    $('#delete-modal').modal('hide');

    let formData = new FormData(this);

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        success: function(data) {
            //get current url
            let url = window.location.href;

            //remove link tr
            $('#commission-tariff-' + $('#commission-tariff-delete-form').data('id')).remove();

            toastr.success('تعرفه با موفقیت حذف شد.');

            //refresh list
            $(".app-content").load(url + " .app-content > *");
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
