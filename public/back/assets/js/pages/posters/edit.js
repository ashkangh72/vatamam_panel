$('#poster-edit-form').submit(function(e) {
    e.preventDefault();

    if (!$(this).data('disabled')) {
        var formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function(data) {
                $('#poster-edit-form').data('disabled', true);
                window.location.href = BASE_URL + "/posters";
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
    }

});

$(".poster-link").autocomplete({
    source: pages
});

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
    placeholder: 'جستوجو',
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

function selectedTemplate(selected) {
    return selected.text;
}
