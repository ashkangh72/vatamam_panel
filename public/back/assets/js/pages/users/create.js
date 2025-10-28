// validate form with jquery validation plugin
jQuery('#user-create-form').validate({
    rules: {
        'name': {
            required: true,
        },
        'password': {
            required: true,
        },
        'password_confirmation': {
            required: true,
            equalTo: "#password"
        },
    },
});
jQuery('#user-edit-form').validate({
    rules: {
        'name': {
            required: true,
        },
        'password_confirmation': {
            equalTo: "#password"
        },
    },
});

$.validator.addMethod(
    "regex",
    function (value, element, regexp) {
        var re = new RegExp(regexp);
        return this.optional(element) || re.test(value);
    },
    "لطفا یک مقدار معتبر وارد کنید"
);

$('#user-create-form, #user-edit-form').submit(function (e) {
    e.preventDefault();
    var form = $(this);

    if ($(this).valid() && !$(this).data('disabled')) {
        var formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function (data) {
                form.data('disabled', true);
                // toastr.success('ویرایش کاربر با موفقیت انجام شد.');
                window.location.href = BASE_URL;// + "/users";
            },
            beforeSend: function (xhr) {
                // block('#main-card');
                xhr.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
            },
            complete: function () {
                // unblock('#main-card');
            },
            cache: false,
            contentType: false,
            processData: false
        });
    }

});
