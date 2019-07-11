/**
 * Created by Vlad on 12.02.2016.
 */


$('img.upload-image').on('click', function (e) {
    node = $(this).parent(".img-upl");
    // $(node).append(modal);
    //$(node).find('.modal').modal('show');
    modal = $(node).find('.modal .modal-body');
    url = "/admin/settings/images";
    getImages(modal, url);
    /* $.ajax({
     type: "GET",
     url: "/admin/settings/images",
     // data: "name=John&location=Boston",
     success: function (msg) {
     console.log(msg);

     /!*if (msg.length > 1) {
     image = [];
     for (i = 0; i < msg.length; i++) {
     image.push(msg[i].image);
     console.log(msg[i].image);
     img = $("<img>").attr('src',msg[i].image);
     $(modal).append(img);
     }
     }*!/
     // console.log(image);
     $(modal).append(msg);
     $('.pagination')
     }
     });*/
});

function getImages(modal, url) {
    $.ajax({
        type: "GET",
        url: url,
        // data: "name=John&location=Boston",
        success: function (img) {
            console.log(img);
            renderImages(modal, img);
        }
    });
}
function renderImages(modal, img) {
    $(modal).html("");
    $(modal).append(img);
    $(modal).find('.pagination li a').on('click', function (e) {
        e.preventDefault();
        getImages(modal, $(this).attr('href'))
    });
    $(modal).find('a.img-select').on('click', function (e) {
        e.preventDefault();
        selectImage(this.href)
    });

    $('#image-upload').on('change', function (e) {
        e.preventDefault();
        e.stopPropagation();
        files = e.target.files;
        var data = new FormData();
        $.each(files, function (key, value) {
            data.append(key, value);
        });
        $.ajax({
            type: "POST",
            url: '/admin/settings/upload-logo',
            data: data,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false,
            success: function (img) {
                console.log(img);
            }
        });
    })

}
function selectImage(url) {
    console.log(url);
    $('.img-upl .upload-image').attr('src', url);
    $('.img-upl input[type="hidden"]').val(url);
    $('.modal').modal('hide');


}

var imageWidget = {
    urlGet: "",
    urlUpload: "",
    modalId:"",
    getImages: function () {
        if (this.url != "") {

        }
    }
};