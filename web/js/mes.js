$(document).ready(function(){
	function log(game){
		var id =  new Date().getTime();
		$('<div class="notif" id="'+id+'">\
    				<span>'+game+'</span>\
    				<p>Матч начался</p\
  		</div>').appendTo('.notif-container').animate({'opacity': '1'}, 700);

  		setTimeout(function(){
  			$('#'+id).animate({'opacity': '0'}, 700, function(){
  				$('#'+id).remove();
  			});
  		}, 3000);
	}


	// function set(array){
	// 	array = JSON.parse(array);
	// 	array.forEach(function(item){
	// 		setTimeout(function(){
	// 			log(item.game);
	// 		}, item.left * 1000);
	// 	});
	// }
	// set(all_fav);
});