$(document).ready(function(){
	$('.game-select').click(function(){
		var id = $(this).data('id');
		$('.active-bracket').addClass('hide-bracket').removeClass('active-bracket');
		$('#bracket-'+id).removeClass('hide-bracket').addClass('active-bracket');
		$('.selector-game .roller-game .active').removeClass('active');
		$(this).addClass('active');
	});
});