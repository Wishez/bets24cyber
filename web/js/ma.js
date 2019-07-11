var cnt = $('#tags-news tr').length - 1;

$(document).on('click', '.delete-tag', function(){
	$(this).closest('tr').remove();
	var i = 0;
	$('#tags-news tr').each(function(){
		$(this).find('input').each(function(){
			var name = $(this).attr('name');
			$(this).attr('name', name.replace(/\[\d+?\]/, '['+i+']'));
		});
		i++;
	});
	cnt = i - 1;
});



$('#add_tag').click(function(){
	var text = $('#search_tag').val().trim();
	var country = $('#search_c').val().trim();
	if(text != ""){
		cnt++;
		$('#tags-news').append('<tr>\
						<input type="hidden" name="streams['+cnt+'][channel]" value="'+text+'">\
						<td>'+text+'</td>\
						<input type="hidden" name="streams['+cnt+'][country]" value="'+country+'">\
						<td>'+country+'</td>\
						<td><label style="font-weight: 100; margin-top: 4px"><input type="checkbox" value="1" name="streams[][main]" style="position: relative; top: 2px;"> Главный</label></td>\
						<td><div class="btn btn-danger b-margin delete-tag"><i class="glyphicon glyphicon-remove"></i></div>\
						</td>\
					</tr>');
		$('#search_tag').val("");
		$('#search_c').val("");

	}
});