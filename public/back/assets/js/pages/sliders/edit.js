$('#slider-edit-form').submit(function(e) {
    e.preventDefault();

    if (!$(this).data('disabled')) {
        var formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function(data) {
                $('#slider-edit-form').data('disabled', true);
               window.location.href = BASE_URL + "/sliders";
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

$(".slider-link").autocomplete({
    source: pages
});


let select2SearchOptions={
    ajax: {
        url: $('option#product').data('action'),
        dataType: 'json',
        processResults: function (data) {
            return {
                results: data.items
            };
        }
    },
    placeholder: 'جستو جو',
    templateResult:createTemplate,
    templateSelection:selectedTemplate,

}
function generateConnectHtml(event) {
    let selected=event.target.value;

    let action=$(event.target).find(`option#${selected}`).data('action');

    let html;
    switch (selected){
        case "product":
            html=`
             <fieldset class="form-group">
                <label>انتخاب محصول</label>
                <div class="custom-file">
                     <select name="link_id" id="link_id" class="form-control"></select>
                </div>
             </fieldset>
           `
            break;
        case 'category':
            html=`
             <fieldset class="form-group">
                <label>انتخاب دسته</label>
                <div class="custom-file">
                     <select name="link_id" id="link_id" class="form-control"></select>
                </div>
             </fieldset>
            `;
    }
    $("#connect-wrapper").html(html);

    select2SearchOptions.ajax.url=action;
    $("#link_id").select2(select2SearchOptions);
}

$("#link_id").select2(select2SearchOptions);

function createTemplate(results) {
    if (results.loading)
        return `درحال جستوجو`;
    let html=$(`
        <span>${results. title}</span><img style="height: 50px;width: 50px;margin: 0 2px 0 6px" src="${results.image}" alt="${results.title}">
    `);
    return html;
}

function selectedTemplate(selected) {

    return  selected.text;
}
