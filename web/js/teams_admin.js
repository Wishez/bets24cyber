$(document).on('click', '.delete-tag', function(){
	$(this).closest('tr').remove();
});



$('#add_tag').click(function(){
	var text = $('#search_tag').val().trim();
	if(text != ""){
		$('#tags-news').append('<tr>\
                    <input type="hidden" name="names[]" value="'+text+'">\
                    <td>'+text+'</td>\
                    <td>\
                        <div class="btn btn-danger b-margin delete-tag">\
                            <i class="glyphicon glyphicon-remove"></i>\
                        </div>\
                    </td>\
                </tr>');
		$('#search_tag').val("");

	}
});