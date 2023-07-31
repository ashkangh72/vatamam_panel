$(document).on('click', '.btn-delete', function() {
    $('#slide-delete-form').attr('action', BASE_URL + '/slides/' + $(this).data('slide'));
    $('#slide-delete-form').data('id', $(this).data('slide'));
});

$('#slide-delete-form').submit(function(e) {
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

            //remove slide tr
            $('#slide-' + $('#slide-delete-form').data('id')).remove();

            toastr.success('اسلایدر با موفقیت حذف شد.');

            //refresh slides list
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

let sortable = $('tbody').sortable({
    opacity: .75,
    handle: '.draggable-handler',
    start: function(e, ui) {
        ui.placeholder.css({
            'height': ui.item.outerHeight(),
            'margin-bottom': ui.item.css('margin-bottom'),
        });
    },
    helper: function(e, tr) {
        let $originals = tr.children();
        let $helper = tr.clone();
        $helper.children().each(function(index) {
            $(this).width($originals.eq(index).width());
        });
        return $helper;
    },

    update: function() {
        $('.slides-sortable').each(function(index, e) {
            saveChanges(index);
        });
    },
});

function saveChanges(group) {
    let sortedIDs = $("#slides-sortable-" + group).sortable("toArray");

    if (!sortedIDs.length) {
        return;
    }

    sortedIDs.forEach(function(value, index) {
        sortedIDs[index] = value.replace('slide-', '');
    });

    $.ajax({
        url: BASE_URL + '/slides/sort',
        type: 'post',
        data: { slides: sortedIDs },
        success: function() {
            //
        },
        beforeSend: function(xhr) {
            xhr.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
            $('#save-changes').show();
        },
        complete: function() {
            $('#save-changes').hide();
        },
    });
}

window.onbeforeunload = function() {
    if (!$('#save-changes').is(":hidden")) {
        return "Are you sure?";
    }
};
