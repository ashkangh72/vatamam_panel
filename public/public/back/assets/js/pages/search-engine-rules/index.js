$(document).on('click', '.btn-delete', function() {
    $('#search_engine_rule-delete-form').attr('action', BASE_URL + '/search-engine-rules/' + $(this).data('id'));
    $('#search_engine_rule-delete-form').data('id', $(this).data('id'));
});

$('#search_engine_rule-delete-form').submit(function(e) {
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

            //remove search_engine_rule tr
            $('#search_engine_rule-' + $(form).data('id') + '-tr').remove();

            toastr.success('قانون با موفقیت حذف شد.');

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

$('#search_engine_rule-create-form').submit(function(e) {
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

            toastr.success('قانون با موفقیت ایجاد شد.');

            //refresh search_engine_rule list
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
