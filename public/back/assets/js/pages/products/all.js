CKEDITOR.config.height = 400;
CKEDITOR.replace('description');

$('.tags').tagsInput({
    'defaultText': 'افزودن',
    'width': '100%',
    'autocomplete_url': BASE_URL + '/get-tags',
});

$('.product-category').select2ToTree({
    rtl: true,
    width: '100%',
});

$('.price-attribute-select').select2ToTree({
    rtl: true,
    width: '100%',
});

$('.product-categories').select2ToTree({
    rtl: true,
    width: '100%',
});

// validate form with jquery validation plugin
jQuery('#product-create-form, #product-edit-form').validate({

    rules: {
        'title': {
            required: true,
        },
        'weight': {
            required: true,
            digits: true
        },
    },
});

//------------ specification group js codes

var groupsCount = groupCount;

$('#add-product-specification-group').click(function () {
    var template = $('#specification-group').clone();

    var group = $('#specifications-area').append(template.html());

    var count = ++groupCount;
    groupsCount++;

    var input = group.find('input[name="specification_group"]');

    input.attr('name', 'specification_group[' + (count) + '][name]');
    input.data('group_name', count);

    groupSortable();

    setTimeout(() => {
        group.find('.specification-group').removeClass('.animated fadeIn');
    }, 700);
});

function groupSortable() {
    $('#specifications-area').sortable({
        opacity: .75,
        start: function (e, ui) {
            ui.placeholder.css({
                'height': ui.item.outerHeight(),
                'margin-bottom': ui.item.css('margin-bottom'),
            });
        },
        helper: function (e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function (index) {
                $(this).width($originals.eq(index).width());
            });
            return $helper;
        },

    });
}

groupSortable();

$(document).on('click', '.remove-group', function () {
    var group = $(this).closest('.specification-group');

    group.addClass('animated fadeOut');

    setTimeout(() => {
        group.remove();
    }, 500);

    groupsCount--;
});

//------------ specifications js codes

$(document).on('click', '.add-specifaction', function () {
    var template = $('#specification-single').clone();

    var specification = $(this).closest('.specification-group').find('.all-specifications').append(template.html());

    var count = ++specificationCount;
    var group_name = $(specification).closest('.specification-group').find('.group-input').data('group_name');

    specification.find('input[name="special_specification"]').attr('name', 'specification_group[' + (group_name) + '][specifications][' + count + '][special]');
    specification.find('input[name="specification_name"]').attr('name', 'specification_group[' + (group_name) + '][specifications][' + count + '][name]');
    specification.find('textarea[name="specification_value"]').attr('name', 'specification_group[' + (group_name) + '][specifications][' + count + '][value]');

    specificationSortable();

    setTimeout(() => {
        specification.find('.single-specificition').removeClass('.animated fadeIn');
    }, 700);

});

$(document).on('click', '.remove-specification', function () {
    var specification = $(this).closest('.single-specificition');

    specification.addClass('animated fadeOut');

    setTimeout(() => {
        specification.remove();
    }, 500);
});

function specificationSortable() {
    $('.all-specifications').sortable({
        opacity: .75,
        start: function (e, ui) {
            ui.placeholder.css({
                'height': ui.item.outerHeight(),
                'margin-bottom': ui.item.css('margin-bottom'),
            });
        },
        helper: function (e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function (index) {
                $(this).width($originals.eq(index).width());
            });
            return $helper;
        },

    });
}

specificationSortable();

//------------ files js codes

function addProductFile() {
    var template = $('#files-template').clone();

    var file = $('#product-files-area').append(template.html()).find('.single-file:last');
    var count = ++filesCount;

    file.find('input[name="title"]').attr('name', 'download_files[' + count + '][title]');
    file.find('select[name="status"]').attr('name', 'download_files[' + count + '][status]');
    file.find('input[name="file"]').attr('name', 'download_files[' + count + '][file]');
    file.find('input[name="file"]').attr('id', 'download_files[' + count + '][id]');
    file.find('label[for="file"]').attr('for', 'download_files[' + count + '][id]');
    file.find('input[name="price"]').attr('name', 'download_files[' + count + '][price]');
    file.find('input[name="discount"]').attr('name', 'download_files[' + count + '][discount]');

    filesSortable();

    setTimeout(() => {
        file.removeClass('animated fadeIn');
    }, 700);
}

$(document).on('click', '#add-product-file', function () {
    addProductFile();
});

$(document).on('click', '.remove-file', function () {
    var file = $(this).closest('.single-file');

    file.addClass('animated fadeOut');

    setTimeout(() => {
        file.remove();
    }, 500);
});

if (filesCount == 0) {
    addProductFile();
}

function filesSortable() {
    $('#product-files-area').sortable({
        opacity: .75,
        start: function (e, ui) {
            ui.placeholder.css({
                'height': ui.item.outerHeight(),
                'margin-bottom': ui.item.css('margin-bottom'),
            });
        },
        helper: function (e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function (index) {
                $(this).width($originals.eq(index).width());
            });
            return $helper;
        },

    });
}

filesSortable();

$('#product-type').on('change', function () {
    if ($(this).val() == 'physical') {
        $('.physical-item').show();
        $('.download-item').hide();
    } else {
        $('.physical-item').hide();
        $('.download-item').show();
    }
});

$('#product-type').trigger('change');

//------------ spectype js codes

$("#specifications_type").autocomplete({
    source: availableTypes
});

$('#specifications_type').change(function () {
    var value = $(this).val();

    if ((availableTypes.includes(value) && !specifications_type_first_change)) {
        addSpecTypeData();
    } else if (availableTypes.includes(value) && groupsCount != 0) {
        $('#specifications-modal').modal('show');
    } else if (availableTypes.includes(value) && groupsCount == 0) {
        addSpecTypeData();
    }

    specifications_type_first_change = true;

    $('#spec-div').show();
});

$('#add-spec-type-data').click(addSpecTypeData);

$('#specifications_type').on('keyup keypress', function (e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) {
        e.preventDefault();
        return false;
    }
});

function addSpecTypeData() {
    $.ajax({
        url: BASE_URL + '/spectypes/spec-type-data',
        type: 'GET',
        data: {
            name: $('#specifications_type').val()
        },
        success: function (data) {
            groupCount = data.groupCount;
            specificationCount = data.specificationCount;
            groupsCount = data.groupCount;

            $('#specifications-area').html(data.view);
            specificationSortable();
            groupSortable();

        },
        beforeSend: function (xhr) {
            block('#specifications-card');
        },
        complete: function () {
            unblock('#specifications-card');
        },
    });
}

//------------ size type js codes

$('#size_type_id').on('change', function () {
    $('#sizes-area').html('');
    $('.add-value').hide();

    if (!$(this).val()) return;

    let select = $(this);

    $.ajax({
        url: select.find('option:selected').data('action'),
        type: 'GET',
        success: function (data) {
            $('#sizes-area').html(data);
            sizeSortable();
            $('.add-value').show();
        },
        beforeSend: function (xhr) {
            block('#sizes-card');
        },
        complete: function () {
            unblock('#sizes-card');
        }
    });
});

$(document).on('click', '.remove-value', function () {
    if ($('.single-value').length == 1) return;

    let value = $(this).closest('.single-value');

    value.addClass('animated fadeOut');

    setTimeout(() => {
        value.remove();
    }, 500);
});

$(document).on('click', '.add-value', function () {
    let template = $('.single-value').first().clone();
    template.addClass('animated fadeIn');
    template.find('input').val('');

    ++sizesCount;

    template.find('input').each(function (i, item) {
        $(item).attr(
            'name',
            `sizes[${sizesCount}][${$(item).data('size-id')}]`
        );
    });

    let value = $('#sizes-area').append(template);

    sizeSortable();

    setTimeout(() => {
        value.find('.single-value').removeClass('animated fadeIn');
    }, 700);
});

function sizeSortable() {
    if ($('.all-sizes').children().length == 0) {
        $('.add-value').hide();
    } else {
        $('.add-value').show();
    }

    $('.all-sizes').sortable({
        opacity: 0.75,
        start: function (e, ui) {
            ui.placeholder.css({
                height: ui.item.outerHeight(),
                'margin-bottom': ui.item.css('margin-bottom')
            });
        },
        helper: function (e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function (index) {
                $(this).width($originals.eq(index).width());
            });
            return $helper;
        }
    });
}

sizeSortable();

//------------ prices js codes
$("#addProductPrice").click(function () {
    let selectedColor = $("#priceColorId :selected");
    let colorId = selectedColor.val();
    let colorName = selectedColor.text();

    let selectedSize = $("#priceSizeId option:selected").map(function(i, el) {
        return {'id' : $(el).val(), 'name' : $(el).text()};
    }).get();

    for (let i=0;i<selectedSize.length;i++) {
        let price;
        if (colorId !== '' && selectedSize[i].id !== '') {
            if ($('#singleColor-'+colorId).length) {
                if (!$('#price-'+ colorId + '-' + selectedSize[i].id).length) {
                    let template = $('#prices-template').clone();
                    price = $('#singleColor-'+colorId).append(template.html());

                    price.find('label[class="label"]').remove();
                    price.find('button[class="remove-product-price-button"]').removeClass('remove-product-price-button').addClass('remove-product-price-button-include')
                } else {
                    continue;
                }
            } else {

                let template = $('#prices-template').clone();

                price = $('#productPrices').append(
                    `<div class="row animated fadeIn single-color" id="singleColor">
                            <div class="container" >
                                <label class="badge badge-light col-md-6 offset-md-3" id="priceRowLabel"></label>
                                <button type="button" class="btn btn-outline-danger waves-effect waves-light custom-padding remove-product-priceColor">حذف</button>
                            </div>
                        `+template.html()+`
                        </div>
                        <div class="col-md-12 single-color-hr"><hr></div>
                        `
                )

                price.find('label[id="priceRowLabel"]').text(`قیمت های رنگ ${colorName}`).attr('id', 'priceRowLabel-'+colorId);
                price.find('label[class="label"]').attr('class', 'label-'+colorId);
                price.find('#singleColor').attr('id', 'singleColor-' + colorId);

                setTimeout(() => {
                    price.find('#singleColor-'+ colorId).removeClass('animated fadeIn');
                }, 700);
            }

            let count = ++priceCount;

            price.find('select[name="color_attribute"]')
                .attr('name', 'prices[' + count + '][attributes][]')
                .attr('id', 'price-' + colorId + '-' + selectedSize[i].id)
                .append(`<option value="${colorId}" selected></option>`);
            price.find('select[name="size_attribute"]')
                .attr('name', 'prices[' + count + '][attributes][]')
                .append(`<option value="${selectedSize[i].id}" selected>${selectedSize[i].name}</option>`);

            price.find('input[name="price"]').attr('name', 'prices[' + count + '][price]');
            price.find('input[name="discount"]').attr('name', 'prices[' + count + '][discount]');
            price.find('input[name="discount_price"]').attr('name', 'prices[' + count + '][discount_price]');
            price.find('input[name="cart_max"]').attr('name', 'prices[' + count + '][cart_max]');
            price.find('input[name="cart_min"]').attr('name', 'prices[' + count + '][cart_min]');
            price.find('input[name="stock"]').attr('name', 'prices[' + count + '][stock]');
            // price.find('input[name="discount_expire"]').attr('name', 'prices[' + count + '][discount_expire]');

            $('#priceColorId option[value=""]').prop("selected", true).trigger("change");
            $('#priceSizeId').val(null).trigger('change');
        }
    }
})

$(document).on('click', '.remove-product-price', function () {
    let price = $(this).closest('.single-price');

    price.addClass('animated fadeOut');

    setTimeout(() => {
        price.remove();
    }, 500);
});
$(document).on('click', '.remove-product-priceColor', function () {
    let color = $(this).closest('.single-color');

    color.addClass('animated fadeOut');

    setTimeout(() => {
        color.remove();
    }, 500);
});

//------------ product attribute pictures
$(document).on('click', '.remove-product-attribute-picture', function () {
    $(this).closest('.single-attribute-picture-row').remove()
});

$("#addProductAttributePicture").click(function () {
    let selectedAttribute = $("#attributeId :selected")
    let attributeId = selectedAttribute.val()
    let attributeName = selectedAttribute.text()

    if (attributeId != '') {
        let attributePictureDropzone = `
            <div class="col-md-12 single-attribute-picture-row" >
                <div id="attributePictureRow-${attributeId}">
                    <label>تصاویر رنگ ${attributeName} ( <small>بهترین اندازه <span class="text-danger">640*640</span> پیکسل میباشد.</small> ). </label>

                    <div class="dropzone dropzone-area mb-2" id="attributePicturesDropzone-${attributeId}">
                        <input type="hidden" id="attributePicturesDropzoneInput-${attributeId}" name="attribute_pictures[${attributeId}]">
                        <div class="dz-message">تصاویر ${attributeName} را به اینجا بکشید</div>
                    </div>
                </div>
                <div class="col-md-12">
                    <button type="button" class="btn btn-flat-danger waves-effect waves-light remove-product-attribute-picture custom-padding btn-block">حذف</button>
                </div>
                <div class="col-md-12"><hr></div>
            </div>
        `;
        $("#attributePictureRows").append(attributePictureDropzone);
        selectedAttribute.attr('disabled', 'disabled')
        $('#attributeId option[value=""]').prop("selected",true).trigger("change");

        let imageNames = [];
        new Dropzone("div#attributePicturesDropzone-"+attributeId, {
            autoProcessQueue: true,
            parallelUploads: 10,
            url: BASE_URL + "/products/image-store",
            addRemoveLinks: true,
            acceptedFiles: '.png,.jpg,.jpeg',

            dictInvalidFileType: 'آپلود فایل با این فرمت ممکن نیست',
            dictRemoveFile: 'حذف',
            dictCancelUpload: 'لغو آپلود',
            dictResponseError: 'خطایی در بارگذاری فایل رخ داده است',

            init: function () {
                this.processQueue();
                this.on("success", function (file, response) {
                    file.upload.filename = response.imagename;

                    $(file.previewElement).data('name', response.imagename);
                    $(file.previewElement).attr('id', response.imagename);

                    imageNames.push(response.imagename);
                    $("#attributePicturesDropzoneInput-" + attributeId).val(imageNames.join(','));
                });
            },

            removedfile: function (file) {

                let name = file.upload.filename;

                if (file.accepted) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        url: BASE_URL + '/products/image-delete',
                        data: { filename: name },
                        success: function (data) {
                            // console.log("File has been successfully removed!!");
                        },
                        error: function (e) {
                            // console.log(e);
                        }
                    });
                }

                let fileRef;
                return (fileRef = file.previewElement) != null ? fileRef.parentNode.removeChild(file.previewElement) : void 0;
            },

            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }
        })
    }
})

//------------ generate slug

$('#generate-product-slug').click(function (e) {
    e.preventDefault();

    var title = $('input[name="meta_title"]').val();

    $.ajax({
        url: BASE_URL + '/product/slug',
        type: 'POST',
        data: {
            title: title
        },
        success: function (data) {
            $('#slug').val(data.slug);
        },
        beforeSend: function (xhr) {
            xhr.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
            $('#slug-spinner').show();
        },
        complete: function () {
            $('#slug-spinner').hide();
        }
    });
});

//------------ dropzone sortable

$('.dropzone-area').sortable({
    items: '.dz-preview',
    opacity: .75,
    start: function (e, ui) {
        ui.placeholder.css({
            'height': ui.item.outerHeight(),
            'margin-bottom': ui.item.css('margin-bottom'),
        });
    },
    helper: function (e, tr) {
        var $originals = tr.children();
        var $helper = tr.clone();
        $helper.children().each(function (index) {
            $(this).width($originals.eq(index).width());
        });
        return $helper;
    },

});

//------------ spectype js codes

$("#brand").autocomplete({
    source: BASE_URL + '/brands/ajax/get',
    delay: 500
});

$('#recommendedProductsSearch').keyup(function() {
    if(this.value.length >= 3) {

        $('#recommendedProducts option:not(:selected)').remove();
        let option = '<option >بارگذاری...</option>'
        $("#recommendedProducts").append(option);

        $.get( BASE_URL + "/products/ajax/get?term=" + this.value, function( data ) {
            let products = data.products
            $('#recommendedProducts option:not(:selected)').remove();
            for(let i=0; i<products.length; i++) {
                let o = new Option(products[i].title, products[i].id);
                let option = `<option value="${products[i].id}">${products[i].id} - ${products[i].title}</option>`
                $("#recommendedProducts").append(option);
            }
        });
    }
});

//------------ publish time picker js codes

$('#publish_date_picker').on('keydown', function (e) {
    e.preventDefault();
    $(this).val('');
    $('#publish_date').val('');
});

let discountPriceInput = $('.discount-price-input');
discountPriceInput.attr('autocomplete', 'off');
$(document).on('keyup', '.discount-price-input', function() {
    let name = $(this).attr('name');
    let price = $(`input[name="${name.replace('discount_price', 'price')}"]`).val();
    let discountedPrice = $(this).val();
    let discountInput = $(`input[name="${name.replace('discount_price', 'discount')}"]`);

    if (!discountedPrice) {
        $(this).next('.form-text').remove();
        discountInput.val(null).trigger('change');
    } else {
        if (discountedPrice !== price) {
            let discount = Math.ceil(100 - (discountedPrice * 100) / price);
            discountInput.val(discount).trigger('change');
        } else {
            discountInput.val(null).trigger('change');
        }

        if (!$(this).next('.form-text').length) {
            $(this).after('<small class="form-text text-success amount-helper"></small>');
        }
        let text = number_format(discountedPrice) + ' تومان';
        $(this).next('.form-text').text(text);
    }
});
discountPriceInput.trigger('keyup');
