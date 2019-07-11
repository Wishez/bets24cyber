


// $(document).ready(function(){
// 			var open = false;



// 	function searchTag(id){
// $(id).keypress(function(event){
// 	if(event.keyCode == 13){
// 		event.preventDefault();
// 	}

// });

// 		$(id).keyup(function(event){
// 			var text = $.trim($(id).val());
// 			if(open){
// 				if(event.keyCode == 40){
// 					var el = $('.search-container .active');
// 					if(el.length > 0){
// 						if(el.index() < $('.search-container .item').length - 1){
// 						el.removeClass('active');
// 						el.next().addClass('active');
// 						if(el.next().index() > 3){
// 							var sc = $('.search-container').scrollTop() + 30;
// 							$('.search-container').scrollTop(sc);
// 						}
// 					}
// 					}else{
// 						$('.search-container .item').first().addClass('active');
// 					}
// 				}else if(event.keyCode == 38){
// 					var el = $('.search-container .active');
// 					if(el.index() > 0){
// 						el.removeClass('active');
// 						el.prev().addClass('active');
// 					if(el.prev().index() < 4){
// 							var sc = $('.search-container').scrollTop() - 30;
// 							$('.search-container').scrollTop(sc);
// 						}
// 					}
// 				}else if(event.keyCode == 13){
// 					var el = $('.search-container .active').first();
// 					if(el.length > 0){
// 						$(id).val(el.text());
// 						$(id).siblings('.search-container').remove();
// 						open = false;

// 					}	
// 					event.preventDefault();

// 				}else if(text.length >= 2 && event.keyCode != 37 && event.keyCode != 39){
// 					$.ajax({
// 						url: "/admin/tags/find",
// 						type: "GET",
// 						data: ({part: text.toLowerCase()}),
// 						dataType: "JSON",
// 						success: function(data){
// 							$('.search-container').empty();
// 							if(data != 0 && $.trim($(id).val()) == text){
// 								data.forEach(function(item){
// 									$('.search-container').append('<div class="item">'+item['tag']+'</div>');
// 								});
// 							}
// 						}
// 					});
// 				}else{
// 					$('.search-container').remove();
// 					open = false;
// 				}
// 			}else{
			
// 			if(text.length >= 2){
// 				if(!open) {
// 					$('<div class="search-container" style="width: '+$(id).width()+'px;"></div>').insertAfter(id).css('opacity', '1');
// 					open = true;
// 				}
				
// 			}else{
// 				if(open){
// 					$(id).siblings('.search-container').remove();
// 					open = false;
// 				}
// 			}
// 				}

// 		});

// 		$(document).on('click', '.search-container .item', function(){
// 			$(this).siblings('.active').removeClass('active');
// 			$(this).addClass('active');
// 			$(id).val($(this).text());
// 			$(this).closest('.search-container').remove();
// 			open = false;

// 		});
// 	}
// 	searchTag('#search_tag');
// 	var i = 0;
// 	$(document).on('click', '.delete-tag', function(){
// 		$(this).closest('tr').remove();
// 		i--;
// 	});
// 	$('#add_tag').click(function(){
// 		var text = $.trim($('#search_tag').val());
// 		if(text != ""){
// 			$('.search-container').remove();
// 			open = false;
// 			$('#tags-news').append('<tr><input type="hidden" name="tags['+i+']" value="'+text+'"><td>'+text+'</td><td><div class="btn btn-danger b-margin delete-tag"><i class="glyphicon glyphicon-remove"></i></div></td></tr>');
// 			i++;
// 			$('#search_tag').val("");

// 		}
// 	});
// });

$(document).ready(function(){
var tags = [];

$.get('/tag-search', function(data){
	tags = data;
	$('#search_tag').autocomplete({source: tags});
}, 'JSON');



$('#search_tag').bind('autocompleteselect', function(){
	$('#add_tag').attr('disabled', false);
});

$('#search_tag').keyup(function(){
	var val = $(this).val().trim();
	console.log(val);
	if(tags.indexOf(val) != -1){
		$('#add_tag').attr('disabled', false);
	}else{
		$('#add_tag').attr('disabled', true);
	}
	event.preventDefault();

});

$(document).on('click', '.delete-tag', function(){
	$(this).closest('tr').remove();
});

$('#add_tag').click(function(){
	var text = $('#search_tag').val().trim();
	if(text != ""){
		$('#tags-news').append('<tr>\
						<input type="hidden" name="tags[]" value="'+text+'">\
						<td>'+text+'</td><td><div class="btn btn-danger b-margin delete-tag"><i class="glyphicon glyphicon-remove"></i></div>\
						</td>\
					</tr>');
		$('#search_tag').val("");

	}
});

});