$(document).ready(function(){

var tags = [];

$.get('/tag-search', function(data){
	tags = data;
	$('.add-tag-tosearch').click(function(){
		if(tag_added <= 10){
			$(this).before('<span class="tag">\
		    			<input type="text" class="tags-search-input" placeholder="Введите тег" name="tags[]" real="0">\
		    			<span class="tag-remove"><i class="fa fa-remove"></i></span>\
		    		</span>');
			$('.tag .tags-search-input').autocomplete({source: tags});
			tag_added++;		
		}

	});
}, 'JSON');


$(document).on('keypress', '.tag .tags-search-input', function(){
	if(event.which == 13){
		event.preventDefault();
	}else{
		if($(this).val().length <= 30){
			var text = $(this).val().length * 8 + 50;
			if(text > $(this).width() + 20){
				$(this).css('width', text);
			}		
		}else{
			event.preventDefault();
		}		
	}


});

var tag_added = 0;

$(document).on('click', '.tag .tag-remove', function(){
	$(this).parent('.tag').remove();
	tag_added--;
});






$('#news_form').submit(function(event){
	var find = false;
	$('.tag .tags-search-input').each(function(){
		if(tags.indexOf($(this).val().trim()) == -1){
			find = true;
			$(this).css('border', '1px solid #8d3536');
		}else{
			$(this).css('border', '');
		}
	});
	if(find){
		event.preventDefault();
	}
	
});


});