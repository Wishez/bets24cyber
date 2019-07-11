/**
 * Created by Vlad on 12.02.2016.
 */


$('img.upload-image').on('click', function (e) {
    node = $(this).parent(".img-upl");
    // $(node).append(modal);
    if (!imageWidget.isAvatar) {
        $(node).find('#Modal' + imageWidget.widgetId + '.modal').modal('show');
        modal = $(node).find('.modal .modal-body');
        $(modal).html("");
        url = imageWidget.urlGet;//  "/admin/settings/images";
        imageWidget.getImages(e, url);
    } else {
        console.log("is Avatar");
        $('#image-upload').click();
        imageWidget.selectAvatar();
    }

});


var imageWidget = {
    isAvatar: false,
    urlGet: "",
    urlUpload: "",
    modalId: "",
    widgetId: "",
    that: "",
    getImages: function (e, url) {
        imageWidget.that = e;
        if (this.url != "") {
            $.ajax({
                type: "GET",
                url: url,
                // data: "name=John&location=Boston",
                beforeSend: function () {
                    console.log("before");
                    $('.img-upl .modal .modal-body').html("<img src='/uploads/loader.gif' class='loading'>")
                },
                success: function (img) {
                    //console.log(img);
                    imageWidget.renderImages(e, img);
                }
            })
        }
    },
    renderImages: function (e, img) {
        target = e.target;
        modal = $(target).parent(".img-upl").find('.modal .modal-body');
        $(modal).html("");
        $(modal).append(img);
        $(modal).find('.pagination li a').on('click', function (e) {
            e.preventDefault();
            imageWidget.getImages(imageWidget.that, this.href)
        });
        $(modal).find('a.img-select').on('click', function (e) {
            e.preventDefault();
            imageWidget.selectImage(this.href)
        });
        $('#image-upload').on('change', function (e) {
            e.preventDefault();
            e.stopPropagation();
            files = e.target.files;
            console.log(files);
            var data = new FormData();

            data.append('image', files[0]);
            console.log(files.length);
            if (files.length > 0) {
                $.ajax({
                    type: "POST",
                    url: imageWidget.urlUpload,
                    data: data,
                    dataType: 'json',
                    processData: false, // Don't process the files
                    contentType: false,
                    success: function (img) {
                        if (img.error) {
                            console.log(img.error)
                        } else {
                            console.log(img.filelink);
                            imageWidget.selectImage(img.filelink)
                        }
                    }
                });
            }

        });
    },
    selectAvatar: function () {
        $('#image-upload').on('change', function (e) {
            console.log('test');
            e.preventDefault();
            e.stopPropagation();
            files = e.target.files;
            console.log(files);
            var data = new FormData();

            data.append('image', files[0]);
            console.log(files.length);
            if (files.length > 0) {
                $.ajax({
                    type: "POST",
                    url: imageWidget.urlUpload,
                    data: data,
                    dataType: 'json',
                    processData: false, // Don't process the files
                    contentType: false,
                    beforeSend: function () {
                        console.log("before");
                        $('.img-upl .upload-image').attr('src', '/uploads/loader.gif' + "?" + Math.random());
                    },
                    success: function (img) {
                        if (img.error) {
                            console.log(img.error)
                        } else {
                            console.log(img.filelink);
                            imageWidget.selectImage(img.filelink);
                        }
                    }
                });
            }
            $('#image-upload').off('change');

        });

    },

    selectImage: function (url) {
        //console.log(url);
        $('.img-upl .upload-image').attr('src', url + "?" + Math.random());
        $('.img-upl input[type="hidden"]').val(url);
        $('.modal').modal('hide');
    }
};