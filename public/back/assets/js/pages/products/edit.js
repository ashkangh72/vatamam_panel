Dropzone.autoDiscover = false;
/* config dropzone uploader for uploading images */
let deletedImageNames = [];
$('.attribute-pictures-dropzone').each(function () {
    let imageNames = [];
    let divId = $(this).attr('id');
    let attributeId = divId.split('-')[1]
    let attributePicturesDropZone = new Dropzone("div#"+divId, {
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        autoProcessQueue: true,
        parallelUploads: 10,
        url: BASE_URL + "/products/image-store",
        addRemoveLinks: true,
        acceptedFiles: '.png,.jpg,.jpeg',

        dictInvalidFileType: 'آپلود فایل با این فرمت ممکن نیست',
        dictRemoveFile: 'حذف',
        dictCancelUpload: 'لغو آپلود',
        dictResponseError: 'خطایی در بارگذاری فایل رخ داده است',
        dictDefaultMessage: 'بارگذاری',
        removedfile: function (file) {
            let filename = file.upload.filename;
            if (file.accepted) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url: BASE_URL + '/products/image-delete',
                    data: { filename: filename },
                    success: function (data) {
                        let attributeDeletedPictureInput = $("#attributeDeletedPicturesInput")
                        deletedImageNames.push(filename);
                        attributeDeletedPictureInput.val(deletedImageNames.join(','));

                        let attributePictureInput = $("#attributePicturesDropzoneInput-" + attributeId)
                        let attributePictures = attributePictureInput.val().split(',')
                        let newAttributePictures = attributePictures.filter(function(image){
                            return image != filename;
                        });
                        attributePictureInput.val(newAttributePictures.join(','));
                    },
                    error: function (e) {
                        console.log(e);
                    }
                });
            }

            let attributePictureInput = $("#attributePicturesDropzoneInput-" + attributeId)
            let attributePictures = attributePictureInput.val().split(',')
            let newAttributePictures = attributePictures.filter(function(image){
                return image != filename;
            });
            attributePictureInput.val(newAttributePictures.join(','));

            let fileRef;
            return (fileRef = file.previewElement) != null ? fileRef.parentNode.removeChild(file.previewElement) : void 0;
        },
        init: function () {
            this.on("success", function (file, response) {
                this.processQueue();
                if (typeof response == "undefined") {
                    $(file.previewElement).data('name', file.image);
                    $(file.previewElement).attr('id', file.image);

                    imageNames.push(file.image);
                    $("#attributePicturesDropzoneInput-" + attributeId).val(imageNames.join(','));
                }
                if (typeof response == "object") {
                    file.upload.filename = response.imagename;

                    $(file.previewElement).data('name', response.imagename);
                    $(file.previewElement).attr('id', response.imagename);

                    imageNames.push(response.imagename);
                    $("#attributePicturesDropzoneInput-" + attributeId).val(imageNames.join(','));
                }
            });

        },
    });

    for (let i in mockImages) {
        if (i === attributeId) {
            mockImages[i].forEach(function (mockFile) {
                attributePicturesDropZone.emit("addedfile", mockFile);
                attributePicturesDropZone.emit("thumbnail", mockFile, mockFile.image);
                attributePicturesDropZone.emit("complete", mockFile);
                attributePicturesDropZone.emit("success", mockFile);
                attributePicturesDropZone.files.push(mockFile);
            })
        }
    }
});

$(document).on('load change click', '.publish_date_picker_discount', function (e) {
    if (!e.target.value) {
        e.target.nextElementSibling.value = '';
    }
    console.log()
    $(this).pDatepicker({
        timePicker: {
            enabled: true,
            meridian: {
                enabled: false,
            },
            second: {
                enabled: false,
            }
        },
        toolbox: {
            // enabled: true,
            calendarSwitch: {
                enabled: false,
            },
        },
        initialValue: false,
        initialValueType: 'persian',

        altField: e.target.nextElementSibling,
        altFormat: 'YYYY-MM-DD H:mm:ss',

        onSelect: function (unixDate) {
            var date = $(e.target.nextElementSibling).val();
            $(e.target.nextElementSibling).val(date.toEnglishDigit());
        }
    });

});

$('#product-edit-form').submit(function (e) {
    e.preventDefault();

    if ($(this).valid() && !$(this).data('disabled')) {

        let form = this;

        let date = $('#publish_date').val();
        $('#publish_date').val(date.toEnglishDigit());

        let formData = new FormData(this);
        formData.append('description', CKEDITOR.instances['description'].getData())

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function (data) {
                if (data == 'success') {
                    $(form).data('disabled', true);
                    window.location.href = $(form).data('redirect');
                }
            },
            beforeSend: function (xhr) {
                block('#main-card');
                xhr.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
            },
            complete: function () {
                unblock('#main-card');
                $('#form-progress').hide();
                $('#form-progress').find('.progress-bar').css('width', '0%');
            },
            xhr: function () {
                let xhr = new window.XMLHttpRequest();
                //Upload progress
                xhr.upload.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        let percentComplete = evt.loaded / evt.total;

                        $('#form-progress').show();
                        $('#form-progress').find('.progress-bar').css('width', percentComplete * 100 + '%');
                        $('#form-progress').find('.progress-bar').text(Math.round(percentComplete * 100) + '%');
                    }
                }, false);

                return xhr;
            },
            cache: false,
            contentType: false,
            processData: false
        });
    }

});

var publishDatePicker;

jQuery(function () {
    publishDatePicker = $("#publish_date_picker").pDatepicker({
        timePicker: {
            enabled: true,
            meridian: {
                enabled: false,
            },
            second: {
                enabled: false,
            }
        },
        toolbox: {
            // enabled: true,
            calendarSwitch: {
                enabled: false,
            },
        },
        initialValue: false,
        initialValueType: 'persian',
        altField: '#publish_date',
        altFormat: 'YYYY-MM-DD H:mm:ss',

        onSelect: function (unixDate) {
            var date = $('#publish_date').val();
            $('#publish_date').val(date.toEnglishDigit());
        },
        onSet: function (unixDate) {
            var date = $('#publish_date').val();
            $('#publish_date').val(date.toEnglishDigit());
        }
    });

    var date = $('#publish_date_picker').val();

    if (date) {
        publishDatePicker.setDate(parseInt(date + '000'));
    }

});
