$(document).ready(function () {

    $('#uploadLogo #uploadform-imagefile').on('change', function () {
        //alert("OK");
        var A = $("#uploadLogo #imageloadstatus");
        var B = $("#uploadLogo button");

        $("#uploadLogo").ajaxForm({
            target: '#preview .img',
            beforeSubmit: function () {
                $('#preview .img').html('');
                A.show();
                B.hide();
            },
            success: function () {
                A.hide();
                B.show();
            },
            error: function () {
                A.hide();
                B.show();
            }
        }).submit();
    });

    $('.multiple-input-list .tbody').sortable();

    

}); 