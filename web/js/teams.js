$(document).ready(function(){
	$('.show-more').click(function(){
		$(this).parent('.open-more').siblings('.content-overview').css('max-height', '');
		$(this).parent('.open-more').remove();
	});
	$('#overview').click(function(){
		$(this).addClass('active');
		$('#results').removeClass('active');
		$('.overview').show();
		$('.results').hide();
	});	
	$('#results').click(function(){
		$(this).addClass('active');
		$('#overview').removeClass('active');
		$('.overview').hide();
		$('.results').show();
	});	
});