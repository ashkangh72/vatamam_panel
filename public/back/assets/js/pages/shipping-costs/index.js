$(document).on('click', '.btn-delete', function() {
    $('#shipping_cost-delete-form').attr('action', BASE_URL + '/shipping-costs/' + $(this).data('id'));
    $('#shipping_cost-delete-form').data('id', $(this).data('id'));
});

$('#shipping_cost-delete-form').submit(function(e) {
    e.preventDefault();

    $('#delete-modal').modal('hide');

    var form = this;

    var formData = new FormData(this);

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        success: function(data) {
            //get current url
            var url = window.location.href;

            //remove shipping_cost tr
            $('#shipping_cost-' + $(form).data('id') + '-tr').remove();

            toastr.success('شهر با موفقیت حذف شد.');

            //refresh shipping_costs list
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

$('#province, #city').select2({
    rtl: true,
    width: '100%',
});

$('#province').change(function() {
    var id = $(this).find(":selected").val();
    $('#city').empty();

    if (!id) {
        return;
    }

    $.ajax({
        type: 'get',
        url: '/province/get-cities',
        data: { id: id },
        success: function(data) {
            $(data).each(function() {
                $('#city').append('<option value="' + $(this)[0].id + '">' + $(this)[0].name + '</option>')
            });
        },

    });
});

$('#shipping-cost-create-form').submit(function(e) {
    e.preventDefault();

    var form = $(this);
    var formData = new FormData(this);

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        success: function(data) {
            //get current url
            var url = window.location.href;

            toastr.success('شهر با موفقیت ایجاد شد.');

            //refresh shipping_costs list
            $("#main-content-div").load(url + " #main-content-div > *");

            form.trigger('reset');

            $('#city').empty();

            $('#province, #city').select2({
                rtl: true,
                width: '100%',
            });
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