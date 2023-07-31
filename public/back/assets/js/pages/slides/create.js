// validate form with jquery validation plugin
jQuery('#slide-create-form').validate({
    errorClass: 'invalid-feedback animated fadeInDown',
    errorPlacement: function (error, e) {
        jQuery(e).parents('.form-group').append(error);
    },
    highlight: function (e) {
        jQuery(e).closest('.form-group').find('input').removeClass('is-invalid').addClass('is-invalid');
    },
    success: function (e) {
        jQuery(e).closest('.form-group').find('input').removeClass('is-invalid');
        jQuery(e).remove();
    },
    invalidHandler: function (form, validator) {
        if (!validator.numberOfInvalids())
            return;

        $('html, body').animate({
            scrollTop: $(validator.errorList[0].element).offset().top - 150
        }, 200);

        $(validator.errorList[0].element).focus();
    },
    rules: {
        'image': {
            required: true,
        },
        'group': {
            required: true,
        },
    },
});

$(".slide-link").autocomplete({
    source: pages
});

$('#slide-create-form').submit(function (e) {
    e.preventDefault();

    if ($(this).valid() && !$(this).data('disabled')) {
        let formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function (data) {
                $('#slide-create-form').data('disabled', true);
                window.location.href = BASE_URL + "/slides";
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
    }
});

// linkable_type
let select2SearchOptions = {
    ajax: {
        url: $('option#auction').data('action'),
        dataType: 'json',
        processResults: function (data) {
            return {
                results: data.items
            };
        }
    },
    placeholder: 'جستو جو',
    templateResult: createTemplate,
    templateSelection: selectedTemplate,
}

function generateConnectHtml(event) {
    let selected = event.target.value;
    let action = $(event.target).find(`option#${selected}`).data('action');

    let html;
    switch (selected) {
        case 'auction':
            html = `
             <fieldset class="form-group">
                <label>انتخاب مزایده</label>
                <div class="custom-file">
                     <select name="linkable_id" id="linkable_id" class="form-control"></select>
                </div>
             </fieldset>
           `
            break;
        case 'category':
            html = `
             <fieldset class="form-group">
                <label>انتخاب دسته</label>
                <div class="custom-file">
                     <select name="linkable_id" id="linkable_id" class="form-control"></select>
                </div>
             </fieldset>
            `;
    }
    $("#connect-wrapper").html(html);

    select2SearchOptions.ajax.url = action;
    $("#linkable_id").select2(select2SearchOptions);
}

$("#linkable_id").select2(select2SearchOptions);

function createTemplate(results) {
    if (results.loading)
        return `درحال جستوجو`;

    return $(`<span>${results.title}</span>`);
}

function selectedTemplate(results) {
    console.log(results)
    return results.title;
}
// end linkable_type



