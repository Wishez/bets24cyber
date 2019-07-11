$(document).ready(function(){
	var show = false;
   $('#user').click(function(){
   	if(show){
       $('#menu_i').hide();
       show = false;
   	}else{
       $('#menu_i').show();
       show = true;
   }
   });
    $('.game-sch').click(function(){
    $('.game-sch').css('border-bottom', 'none');
    $(this).css('border-bottom', '2px solid #568e9c');
    $(this).siblings('#game-as').val($(this).data('i'));
  });
});