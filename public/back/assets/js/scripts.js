String.prototype.replaceAll = function (search, replacement) {
    let target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};

String.prototype.toPersianCharacter = function () {
    let string = this;
    const obj = {
        'ك': 'ک',
        'دِ': 'د',
        'بِ': 'ب',
        'زِ': 'ز',
        'ذِ': 'ذ',
        'شِ': 'ش',
        'سِ': 'س',
        'ى': 'ی',
        'ي': 'ی',
        '١': '۱',
        '٢': '۲',
        '٣': '۳',
        '٤': '۴',
        '٥': '۵',
        '٦': '۶',
        '٧': '۷',
        '٨': '۸',
        '٩': '۹',
        '٠': '۰',
    };
    Object.keys(obj).forEach(function (key) {
        string = string.replaceAll(key, obj[key]);
    });
    return string
}

function block(el) {
    var $body = $('body');

    var block_ele = $(el);

    var reloadActionOverlay;
    if ($body.hasClass('dark-layout')) {
        var reloadActionOverlay = '#10163a';
    } else {
        var reloadActionOverlay = '#fff';
    }

    // Block Element
    block_ele.block({
        message: '<div class="feather icon-refresh-cw icon-spin font-medium-2 text-primary"></div>',
        overlayCSS: {
            backgroundColor: reloadActionOverlay,
            cursor: 'wait',
        },
        css: {
            border: 0,
            padding: 0,
            backgroundColor: 'none',
        },
    });
}

function unblock(el) {
    $(el).unblock();
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    },
    error: function(data) {

        if (data.status == 403) {
            toastr.error('اجازه ی دسترسی ندارید', 'خطا');
            return;
        } else if (data.status == 429) {
            toastr.error('تعداد درخواست ها بیش از حد مجاز است لطفا پس از دقایقی مجدد تلاش کنید', 'خطا');
            return;
        }else if (data.status==0){
            return;
        } else if (!data.responseJSON.errors) {
            toastr.error('خطایی رخ داده است', 'خطا');
            return;
        }

        for (var key in data.responseJSON.errors) {
            // skip loop if the property is from prototype
            if (!data.responseJSON.errors.hasOwnProperty(key)) continue;

            var obj = data.responseJSON.errors[key];
            for (var prop in obj) {
                // skip loop if the property is from prototype
                if (!obj.hasOwnProperty(prop)) continue;

                toastr.error(obj[prop], 'خطا');
            }
        }
    },
});

function reloadDiv(el) {
    //get current url
    var url = window.location.href;

    //refresh comments list
    $(el).load(url + ' ' + el + ' > *');
}

String.prototype.toEnglishDigit = function() {
    var find = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    var replace = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    var replaceString = this;
    var regex;
    for (var i = 0; i < find.length; i++) {
        regex = new RegExp(find[i], 'g');
        replaceString = replaceString.replace(regex, replace[i]);
    }
    return replaceString;
};

$('#video-helpe-modal').on('show.bs.modal', function() {
    $('#video-helpe-modal iframe').each(function() {
        var src = $(this).data('src');
        $(this).attr('src', src);
    });
});

$('#video-helpe-modal').on('hide.bs.modal', function() {
    $('#video-helpe-modal iframe').each(function() {
        var src = $(this).attr('src');
        $(this).attr('src', src);
    });
});

$('#video-helpe-modal .collapse-header').on('click', function() {
    $('#video-helpe-modal iframe').each(function() {
        var src = $(this).attr('src');
        $(this).attr('src', src);
    });
});

//---------------- amount input
$('.amount-input').attr('autocomplete', 'off');

$(document).on('keyup', '.amount-input', function() {
    if (!$(this).val()) {
        $(this).next('.form-text').remove();
        return;
    }

    if (!$(this).next('.form-text').length) {
        $(this).after('<small class="form-text text-success amount-helper"></small>');
    }

    var text = number_format($(this).val()) + ' تومان';

    $(this).next('.form-text').text(text);
});

$('.amount-input').trigger('keyup');

$('#province, #city').select2({
    rtl: true,
    width: '100%'
});

$('#province').change(function () {
    var id = $(this).find(':selected').val();

    if (!id) return;

    $('#city').empty();

    $.ajax({
        type: 'get',
        url: $('#province').data('action'),
        data: {id: id},
        success: function (data) {
            $(data).each(function () {
                selected =
                    $(this)[0].id == $('#city').data('id') ? 'selected' : '';

                $('#city').append(
                    `<option value="${$(this)[0].id}" ${selected}>${
                        $(this)[0].name
                    }</option>`
                );
            });
        },
        beforeSend: function () {
            block('#city-div');
        },
        complete: function () {
            unblock('#city-div');
        }
    });
});
