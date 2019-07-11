$('.where-search-chose-block .where-search').click(function(){
	var value = $(this).attr('value');
	$('#where').val(value);
	$('.where-search-chose-block .active').removeClass('active');
	$(this).addClass('active');
});