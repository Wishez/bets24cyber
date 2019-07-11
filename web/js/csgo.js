const ROUND_END = 0;
const ROUND_START = 1;
const BOMB = 2;

const TIME_PLAY = 115;
const TIME_BOMB = 35;


$(document).ready(function(){

var games = {};


var bomb = {
	set: false,
	active: false,
	setBomb: function(){
		if(!this.set){
			$('#timer').addClass('none');
			$('#bomb').removeClass('none');

			this.interval = setInterval(function(){
				if(bomb.active){
					bomb.active = false;
					$('#bomb').css('background-image', 'url("/img/common/bomb.png")');
				}else{
					bomb.active = true;
					$('#bomb').css('background-image', 'url("/img/common/bomb_active.png")');
				}
			}, 600);
			this.set = true;
		}
	},
	removeBomb: function(){
		if(this.set){
			clearInterval(this.interval);
			$('#timer').removeClass('none');
			$('#bomb').addClass('none');			
			this.set = false;
		}
	}
}

var timer = {
   time: 0,
   stop: false,
   tick: function(){
        this.time--;
        if(this.time < 0) return;
        var seconds = parseInt(this.time % 60, 10);
        if(seconds < 10){
        	seconds = '0'+seconds;
        }
        var st_time = parseInt(this.time / 60, 10)+':'+seconds;
        $('#timer').text(st_time);
   },

   run: function(){
         if(!timer.stop){
            timer.tick();
            timer.timerRec = setTimeout(timer.run, 1000);    
         }

   },
   runTimer: function(time){
   		this.time = time;
      	this.stop = false;
      	this.run();
   },
   stopTimer: function(){
      clearTimeout(this.timerRec);
      this.stop = true;
   }
}


	function renderPlayers(players, side){
		var table;
		if(side == 0){
			table = $('#ct-players');
		}else{
			table = $('#t-players');
		}
		table.empty();
		players.forEach(function(player){
					var inactive = !player.alive ? 'class="inactive"' : '';
					table.append('<tr '+inactive+'>\
                        <td class="player-body">'+player.name+'</td>\
                        <td>'+player.money+'$</td>\
                        <td>'+player.kills+'</td>\
                        <td>'+player.deaths+'</td>\
                        <td>'+player.assists+'</td>\
                        <td>'+player.rating+'</td>\
                        </tr>');

		});
	}
	function etype(num){
			if(num == 2){
				return 'http://static.hltv.org/images/scoreboard/ct_win.svg';
			}else if(num == 3){
				return 'http://static.hltv.org/images/scoreboard/bomb_defused.svg';
			}else if(num == 5){
				return 'http://static.hltv.org/images/scoreboard/stopwatch.svg';
			}else if(num == 7){
				return 'http://static.hltv.org/images/scoreboard/emptyHistory.svg';
			}else if(num == 1){
				return 'http://static.hltv.org/images/scoreboard/t_win.svg';
			}else if(num == 4){
				return 'http://static.hltv.org/images/scoreboard/bomb_exploded.svg';
			}
	}
	function render(game){
		$('#wait-game').hide();
		$('#ct-name').text(game.data.teams['2'].name);
		$('#ct-score').text(game.data.teams['2'].score);
		$('#ct-img').attr('src', teams_logo[game.data.teams['2'].id]);
		
		$('#t-name').text(game.data.teams['1'].name);
		$('#t-score').text(game.data.teams['1'].score);
		$('#t-img').attr('src', teams_logo[game.data.teams['1'].id]);

		$('#round').text('Раунд: '+game.data.currentRound);

		if(game.status == ROUND_END){
			timer.stopTimer();
			$('#timer').text('Завершился');
			bomb.removeBomb();	
		}else if(game.status == ROUND_START){
			timer.stopTimer();
			if(game.time > 10){
				timer.runTimer(game.time);
			}	
			bomb.removeBomb();	
		}else if(game.status == BOMB){
			timer.stopTimer();
			bomb.setBomb();			
		}
		$('#ts-1').empty();
		$('#ts-2').empty();
		$('#ts-3').empty();
		$('#ts-4').empty();
		for(var i = 0; i < Math.min(15, game.data.teams['1'].history.length); i++){
			$('#ts-2').append('<div class="round-status">\
                                <img src="'+etype(game.data.teams['1'].history[i].type)+'">\
                            </div>');
		}
		for(var i = 15; i < game.data.teams['1'].history.length; i++){
			$('#ts-4').append('<div class="round-status">\
                                <img src="'+etype(game.data.teams['1'].history[i].type)+'">\
                            </div>');
		}
		for(var i = 0; i < Math.min(15, game.data.teams['2'].history.length); i++){
			$('#ts-1').append('<div class="round-status">\
                                <img src="'+etype(game.data.teams['2'].history[i].type)+'">\
                            </div>');
		}
		for(var i = 15; i < game.data.teams['2'].history.length; i++){
			$('#ts-3').append('<div class="round-status">\
                                <img src="'+etype(game.data.teams['2'].history[i].type)+'">\
                            </div>');
		}
		renderPlayers(game.data.teams['2'].players, 0);
		renderPlayers(game.data.teams['1'].players, 1);
	}
	function renderLastPlayers(players, side){
		var table;
		if(side == 0){
			table = $('#ct-players');
		}else{
			table = $('#t-players');
		}
		table.empty();
		players.forEach(function(player){
			if(player.team == side){
					table.append('<tr>\
                        <td class="player-body">'+player.name+'</td>\
                        <td>XXXX</td>\
                        <td>'+player.k+'</td>\
                        <td>'+player.d+'</td>\
                        <td>'+player.a+'</td>\
                        <td>'+player.r+'</td>\
                        </tr>');				
			}


		});
	}
	function renderLast(data){
		$('#wait-game').hide();
		$('#ct-name').text(data.game.team1_name);
		$('#ct-score').text(data.game.score_left);
		if(data.game.team1_id in teams_logo){
			$('#ct-img').attr('src', teams_logo[data.game.team1_id]);
			$('#t-img').attr('src', teams_logo[data.game.team2_id]);

		}else{
			$('#ct-img').attr('src', teams_logo[data.game.team2_id]);
			$('#t-img').attr('src', teams_logo[data.game.team1_id]);

		}
		$('#t-name').text(data.game.team2_name);
		$('#t-score').text(data.game.score_right);

		$('#round').text('Раунд: '+(parseInt(data.game.score_left, 10) + parseInt(data.game.score_right, 10)));

		timer.stopTimer();
		$('#timer').text('Завершился');
		bomb.removeBomb();

		$('#ts-1').empty();
		$('#ts-2').empty();
		$('#ts-3').empty();
		$('#ts-4').empty();

		data.game.history.team1.first_side.forEach(function(item){
			$('#ts-1').append('<div class="round-status">\
                                <img src="'+item+'">\
                            </div>');
		});
		data.game.history.team1.second_side.forEach(function(item){
			$('#ts-2').append('<div class="round-status">\
                                <img src="'+item+'">\
                            </div>');
		});
		data.game.history.team2.first_side.forEach(function(item){
			$('#ts-3').append('<div class="round-status">\
                                <img src="'+item+'">\
                            </div>');
		});
		data.game.history.team2.second_side.forEach(function(item){
			$('#ts-4').append('<div class="round-status">\
                                <img src="'+item+'">\
                            </div>');
		});

		renderLastPlayers(data.players, 0);
		renderLastPlayers(data.players, 1);

	}
	var server = io(':2000',{'max reconnection attempts':Infinity, secure: true}); 



	if($('.dota-game-select').attr('active') == '1'){
		var active = true;
		server.emit('track', {match_id: $('.dota-game-select').attr('id')});
	}else{
		var active = false;
	}

	server.on('score', function(score){
		if(active){
			games['0'] = score;
			render(score);		
		}

	});

	server.on('start', function(){
		if(active){
			timer.stopTimer();
			timer.runTimer(TIME_PLAY);
			bomb.removeBomb();
		}			
	});
	server.on('end', function(){
		if(active){
			timer.stopTimer();
			$('#timer').text('Завершился');	
			bomb.removeBomb();	
		}		
	});
	function getMatch(id, callback){
		$.ajax({
         url: "/get-csgo-id",
         type: "GET",
         data: {'match_id': id},
         dataType: "json",
         success: function(data){
         	callback(data);

         }
      
      });  
	}
	
	$('.dota-game-select .selecter-dota').click(function(){
		$('.dota-game-select .active').removeClass('active');
		$(this).addClass('active');
		var id = parseInt($(this).attr('match-id'), 10);

		if(id == 0){
			active = true;
			render(games['0']);
		}else{
			active = false;
			if(id in games){
				renderLast(games[id]);
			}else{
				getMatch(id, function(data){
					games[id] = data;
					renderLast(data);
				});
			}
		}	

	});


function updateEventCsgo(){
  var now_games = [];
  $('.selecter-dota').each(function(){
  	var x = $(this).attr('match-id');
  	if(x != 0){
      now_games.push(x);
  	}
  });
      $.ajax({
         url: "/get-new-csgo-games",
         type: "GET",
         data: {'games': now_games, 'match_id': match_id},
         dataType: "json",
         success: function(data){
            match_over = data.over;
            if(data.over == 1){
              $('.over').text('Матч завершился');
            }else{
	            if($('.dota-game-select').attr('active') == 0 && data.active == 1){
	            	server.emit('track', {match_id: $('.dota-game-select').attr('id')});
	            	var l = $('.selecter-dota').length;
	            	$('.dota-game-select .active').removeClass('active');
	            	$('.dota-game-select').append('<div class="selecter-dota active" match-id="0">Игра '+(l + 1)+'</div>');
	            	    if($('.over').text() != 'Live'){
      						$('#wait-game').show();
    				}
    

	            }
	            if('match_id' in data){
	            	$('.dota-game-select .selecter-dota').last().attr('match-id', data.match_id);
	            	if($('.dota-game-select').attr('active') == 1 && data.active == 1){
	            		var l = $('.selecter-dota').length;
	            		$('.dota-game-select .active').removeClass('active');
	            		$('.dota-game-select').append('<div class="selecter-dota active" match-id="0">Игра '+(l + 1)+'</div>');
					    if($('.over').text() != 'Live'){
					      $('#wait-game').show();
					    }
    

	            	}
	            }

            	
        	}
        	$('.dota-game-select').attr('active', data.active);
            $.ajax({ // инициaлизируeм ajax зaпрoс
                type: 'POST', // oтпрaвляeм в POST фoрмaтe, мoжнo GET
                url: '/get-score', // путь дo oбрaбoтчикa, у нaс oн лeжит в тoй жe пaпкe
                data: {
                    'match_id': match_id
                },
                success: function (data) { // сoбытиe пoслe удaчнoгo oбрaщeния к сeрвeру и пoлучeния oтвeтa
                    $('.score').text(data);
                }

            });
         }
      });

}

var updtcsgo = setInterval(function(){
  if(match_over == 0){
      updateEventCsgo();
  }else{
    clearInterval(updtcsgo);
}
  
}, 3 * 60 * 1000);
   $('.csgo-players th[sort-type]').hover(function(){
      var sort_type = $(this).attr('sort-type'); 
      var className = sort_type == 1 ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up';
      $('<i class="sort_dota glyphicon '+className+'"></i>').appendTo(this).animate({'opacity': '1'}, 300);
   }, function(){
      var s = $(this).find('.sort_dota');
      s.stop().animate({'opacity': '0'}, 300, function(){
         s.remove();
      })
   });
   $('.csgo-players th[sort-type]').click(function(){
      var sort_type = $(this).attr('sort-type'); 
      var index = $(this).index();
      
      $(this).closest('.csgo-players').find('tbody tr').sort(function(a, b) { // сортируем
             var a1 = parseInt($(a).find('td').eq(index).text(), 10);
             var b1 = parseInt($(b).find('td').eq(index).text(), 10);

             if(sort_type == 0){
               if(a1 > b1){
                  return -1;
               }else if(a1 < b1){
                  return 1;
               }
             }else{
               if(a1 > b1){
                  return 1;
               }else if(a1 < b1){
                  return -1;
               }
             }
             return 0;             
      }).appendTo($(this).closest('.csgo-players').find('tbody'));
      if(sort_type == 0){
         $(this).attr('sort-type', 1);
         $(this).find('i').removeClass('glyphicon-chevron-up');
         $(this).find('i').addClass('glyphicon-chevron-down');
      }else{
         $(this).attr('sort-type', 0);
         $(this).find('i').removeClass('glyphicon-chevron-down');
         $(this).find('i').addClass('glyphicon-chevron-up');
         
      }
   });

 var open = false;
$('.csgo-expend').click(function(){
   if(!open){
      $('.hide-csgo').css('height', 'auto');
      open = true;
      $('.csgo-expend').text('Закрыть');

   }else{
      $('.hide-csgo').css('height', '0px');
      open = false;
      $('.csgo-expend').text('Открыть');
   }
});
});