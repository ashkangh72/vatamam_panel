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
        title: 'آیا میخواهید درخواست بازاریابی را تایید کنید ؟',
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
                url: BASE_URL + '/users/marketing-requests/accept',
                contentType: "application/json",
                dataType: "json",
                data: JSON.stringify({
                    marketing_request_id : $(this).data('marketing-request')
                }),
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
        title: 'آیا میخواهید درخواست بازاریابی را رد کنید ؟',
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
                url: BASE_URL + '/users/marketing-requests/reject',
                contentType: "application/json",
                dataType: "json",
                data: JSON.stringify({
                    marketing_request_id : $(this).data('marketing-request')
                }),
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
