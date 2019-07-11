
$(document).ready(function(){
	var load = false;
	function render(data, id){
		// console.log($parent.data('rules'));
		var $parent = $('#'+id);
		var rules = $parent.data('rule');
		var buffer = '';
		rules.rows.forEach(function(row){
			var index = $parent.data('index');
			buffer += '<tr>';
			row.forEach(function(item){
				if(item.text){
					var value = item.from in data && data[item.from] != "" ? data[item.from] : item.default;
					buffer += '<td>\
							<input type="hidden" pp="'+item.name+'" value="'+value+'">\
							<div class="replace-input">'+value+'</div>\
						</td>';
				}else if(item.input){
					var value = item.from in data && data[item.from] != "" ? data[item.from] : "";
					var input = item.ht.replace(/val\.name/, 'pp="'+item.name+'"');
					input = input.replace(/val\.value/, value);
					buffer += '<td>'+input+'</td>';
				}else if(item.check){
					var value = item.from in data && data[item.from] != "" ? data[item.from] : item.default;
					var checked = parseInt(value, 10) == 1 ? 'checked' : '';
					buffer += '<td><label><input type="checkbox" value="'+value+'" pp="'+item.name+'" '+checked+'>'+item.t+'</label></td>';
				}
			});
			buffer += '<td><button class="btn btn-danger b-margin delete"><i class="glyphicon glyphicon-remove"></i></button></td>';
			buffer += '</tr>';
			
			

			$parent.data('index', index + 1);
			$parent.find('.form-league').prepend(buffer);
		});
	}
	updates.forEach(function(item){
		render(item.data, item.id);
	});
	function empty(){
		$('.table-down').each(function(){
			$(this).data('index', 0);
			$(this).find('.form-league').empty();
		});
	}
	$('body').on('click', '.delete', function(){
		var n = $(this).closest('.table-down').data('index');
		$(this).closest('.table-down').data('index', n - 1);
		var index = n - 1;

		var tr = $(this).closest('tr');
		console.log($(this).closest('.table-down').find('tr').length);
		$(this).closest('.table-down').find('tr').each(function(){
			if(tr.get(0) !== $(this).get(0)){
				index--;
				console.log(index);
				$(this).find('td').each(function(){
					var name = $(this).find('input').attr('name');
					if(name != undefined){
				 		$(this).find('input').attr('name', name.replace(/\[\d+?\]/, '['+index+']'));
					}
				});
			}

			
		});
		tr.remove();

        
	});

	$(document).on('click', '.replace-input', function(){
		var text = $(this);
		text.hide();
		var input = $(this).siblings('input');
		input.css('width', input.val().length * 7 + 20);
		input.attr('type', 'text');
		input.focus();
		input.blur(function(){
			input.attr('type', 'hidden');
			text.text(input.val());
			text.show();
		});
	});
	$('.table-down .add-row').click(function(){
		var data = {};
		$(this).siblings('input').each(function(){
			var key = $(this).attr('name').match(/\[(.+?)\]/)[1];
			data[key] = $.trim($(this).val());
			$(this).val("");
		});
		render(data, $(this).closest('.table-down').attr('id'));

	});


	function log(text){
		$('#status-bar').text(text);
	}

	function parseLink(link){
		var pattern = /^http:\/\/wiki\.teamliquid\.net\/(.+?)\/(.+?)$/;
		if(pattern.test(link)){
			var values = link.match(pattern);
			var game = values[1] == 'dota2' ? 0 : 1;
			return {'league_id': values[2], 'game': game};
		}
		return null;
	}
	function loadData(link, callback, max_request = 5){
		var params = parseLink(link);
		if(!params){
			log('Неверная ссылка на турнир');
			callback();
			return;
		}
		$.ajax({
			url: "/league/get-league",
			type: "POST",
			data: params,
			dataType: "JSON",
			beforeSend: function(){
				log('Загрузка данных..');
			},
			success: function(data){
				if('err' in data){
					if(max_request > 0){
						loadData(link, callback, max_request - 1);
					}else{
						log(data.err);
						callback(null);
					}

				}else{
					log('Загружено');
					callback(data);
				}
			}
		});
	}

function saveLeague(data, callback){
		$.ajax({
			url: "/league/save-league",
			type: "POST",
			data: data,
			dataType: "JSON",
			beforeSend: function(){
				log('Сохранение турнира...');
			},
			success: function(data){
				log('Загружено');
				callback(data);
			}
		
		});
}
function getTeamList(teams, game, callback){
	    $.ajax({
			url: "/league/get-teamlist",
			type: "POST",
			data: {teams: teams, game: game},
			dataType: "JSON",
			beforeSend: function(){
				log('Получение списка команд..');

			},
			success: function(data){
				callback(data);
			}
		
		});
}

function updateTeams(teams, game, callback, index = 0){
	if(index < teams.length){
		$.ajax({
			url: "/league/update-team",
			type: "POST",
			data: {team: teams[index], game: game},
			dataType: "html",
			beforeSend: function(){
				log('Сохранение команд ('+(index + 1)+' из '+teams.length+')...');

			},
			success: function(){

				updateTeams(teams, game, callback, index + 1);
			}
		
		});
	}else{
		callback();
	}
}
function save(data, callback){
	load = true;
	var teams = [];
	data['matches'].forEach(function(item){
		teams.push(item.team1);
		teams.push(item.team2);
	});
	teams = teams.filter(function(value, index, self){
		return self.indexOf(value) === index;
	});
	getTeamList(teams, data.league.game, function(teams){
		updateTeams(teams, data.league.game, function(){
			saveLeague(data, function(response){
				if('err' in response){
					log(response.err);
				}else{
					log('Загружено')
				}
				callback();
			});
		});
	})

}

$('#load-image').change(function(){
    var fd = new FormData();

    //console.log(this.files);

    fd.append("img-a", this.files[0]);

    $.ajax({
        url: '/league/load-img',
        type: "POST",
        data: fd,
        processData: false,
        contentType: false,
        success: function(data){
        	//console.log(data);
        	$('[prop="image"]').val(data);
        }
    });
});


	$('#loadData').click(function(){
			var button = $(this);
			button.text("Загрузка...");
			button.prop("disabled", true);
			loadData($('#link').val().trim(), function(data){
				button.text("Заполнить");
				button.prop("disabled", false);
				
				if(data != null){
					for(var prop in data.league){
						if(prop != 'event'){
							$('[prop="'+prop+'"]').val(data.league[prop]);
						}else if(data.league.event == 1){
							$('[prop="event"]').attr('checked', true);
						}else{
							if(!$('[prop="event"]').prop("checked")){
								$('[prop="event"]').attr('checked', false);
							}
							
						}
					}
					empty();
					data.matches.forEach(function(item){
						item.score = item.score_left+'-'+item.score_right;
						render(item, 'matches');
					});
					data.quals.forEach(function(item){
						render(item, 'quals');
					});
					data.teams.forEach(function(item){
						render(item, 'teams');
					});
				}
			});
	});
	$('#save').click(function(){
		if(!load){
			var league_data = {
				'league': {},
				'matches': [],
				'teams': []
			};
			$('#teams .table-league tr').each(function(){
				var team = {};
				$(this).find('input').each(function(){
					team[$(this).attr('pp')] = $(this).val();
				});
				league_data['teams'].push(team);
			});
			$('#matches .table-league tr').each(function(){
				var match = {};
				$(this).find('input').each(function(){
					if($(this).attr('pp') == 'over'){
						if($(this).prop("checked")){
							match['over'] = 1;
						}else{
							match['over'] = 0;
						}
					}else{
						match[$(this).attr('pp')] = $(this).val();
					}
					
				});
				var score_match = match['score'].match(/(\d)-(\d)/);
				delete match['score'];
				match['score_left'] = score_match[1];
				match['score_right'] = score_match[2];
				league_data['matches'].push(match);
			});
			$('[prop]').each(function(){
				if($(this).attr('prop') == 'event'){
					if($(this).prop("checked")){
						league_data['league']['event'] = 1;
					}else{
						league_data['league']['event'] = 0;
					}
				}else{
					league_data['league'][$(this).attr('prop')] = $(this).val().trim();
				}
			});
			if(league_data['league']['link'] != ""){
				var params = parseLink(league_data['league']['link']);
				league_data['league']['league_id'] = params['league_id'];
				league_data['league']['game'] = params['game'];

				delete league_data['league']['link'];
				$('#save').text("Загрузка...");
				$('#save').prop("disabled", true);
				save(league_data, function(){
					$('#save').text("Сохранить");
					$('#save').prop("disabled", false);
					load = false;
				});
			}else{
				log('Заполните ссылку на турнир');
			}
			
		}
		
	});
	$('body').on('click', '.load-qual', function(){
		var button = $(this);
		load = true;
		button.text('Загрузка..');
		button.prop("disabled", true);
		var qname = $(this).closest('tr').find('[pp="name"]').val().trim();
		loadData($(this).closest('tr').find('[pp="link"]').val().trim(), function(data){
				var link = parseLink($('#link').val().trim());
				data.league.main = link['league_id'];
				data.league.qname = qname;
				data.league.event = 1;
				console.log(data);
				save(data, function(){
					button.text('Загружено');
					load = false;
				});
		});
	});
});