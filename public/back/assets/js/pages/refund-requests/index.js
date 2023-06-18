$(document).on('click', '.btn-delete', function() {
    $('#refund-request-delete-form').attr('action', BASE_URL + '/refund-requests/' + $(this).data('refund-request'));
    $('#refund-request-delete-form').data('id', $(this).data('refund-request'));
});
$('#refund-request-delete-form').submit(function(e) {
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

            //remove refund-request tr
            $('#refund-request-' + $('#refund-request-delete-form').data('id') + '-tr').remove();

            toastr.success('با موفقیت حذف شد.');

            //refresh refund requests list
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

$(document).on('click', '.show-refund-request', function() {
    $.ajax({
        url: BASE_URL + '/refund-requests/' + $(this).data('refund-request'),
        type: 'GET',
        success: function(data) {
            $('#refund-request-detail').empty();
            $('#refund-request-detail').append(data);
            $('#show-modal').modal('show');
        },
        beforeSend: function(xhr) {
            block('#main-card');
        },
        complete: function() {
            unblock('#main-card');
        }
    });

});

$(document).on('click', '.btn-accept', function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
        title: 'آیا میخواهید درخواست بازگردانی را تایید کنید ؟',
        text: "",
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'تایید شود',
        cancelButtonText: 'خیر',
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: 'POST',
                url: BASE_URL + '/refund-requests/accept/'+$(this).data('refund-request'),
                data: {},
                success: function (data) {
                    $(".modal").modal('hide');
                    toastr.success('با موفقیت تایید شد.');

                    //refresh refund requests list
                    let url = window.location.href;
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
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire(
                'مشکلی پیش آمد دوباره تلاش کنید !',
                '',
                'error'
            )
        }
    })
});

$(document).on('click', '.btn-reject', function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
        title: 'آیا میخواهید درخواست بازگردانی را رد کنید ؟',
        text: "",
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'رد شود',
        cancelButtonText: 'خیر',
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: 'POST',
                url: BASE_URL + '/refund-requests/reject/'+$(this).data('refund-request'),
                data: {},
                success: function (data) {
                    $(".modal").modal('hide');
                    toastr.success('با موفقیت رد شد.');

                    //refresh refund requests list
                    let url = window.location.href;
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
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire(
                'مشکلی پیش آمد دوباره تلاش کنید !',
                '',
                'error'
            )
        }
    })
});

$(document).on('click', '.btn-receive', function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-success',
            cancelButton: 'btn btn-danger'
        },
        buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
        title: 'آیا محصول را دریافت کرده اید ؟',
        text: "",
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'دریافت کرده ام',
        cancelButtonText: 'خیر',
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: 'POST',
                url: BASE_URL + '/refund-requests/receive/'+$(this).data('refund-request'),
                data: {},
                success: function (data) {
                    $(".modal").modal('hide');
                    toastr.success('با موفقیت دریافت شد.');

                    //refresh refund requests list
                    let url = window.location.href;
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
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire(
                'مشکلی پیش آمد دوباره تلاش کنید !',
                '',
                'error'
            )
        }
    })
});
