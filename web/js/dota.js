
// // function draw(data){



// //     $('#chart').highcharts({
// //         title: {
// //             text: 'Коэффициенты БК'
// //         },
// //         yAxis: {
// //             title: {
// //                 text: 'Коэффициенты'
// //             },
// //             plotLines: [{
// //                 value: 0,
// //                 width: 1,
// //                 color: '#808080'
// //             }]
// //         },
// //         tooltip: {
// //             valueSuffix: ''
// //         },
// //         legend: {
// //             layout: 'vertical',
// //             align: 'right',
// //             verticalAlign: 'middle',
// //             borderWidth: 0
// //         },
// //         series: data
// //     });
// //  }
// //  if(typeof data == undefined){
// //    var data = 0;
// //  }
  
// //   if(data != 0) draw(data);
 
function getDotaTime(timestamp){
     var m = parseInt(timestamp / 60, 10);
    var s = parseInt(timestamp % 60, 10);
    if(s < 10){
      s = '0'+s;
    }
   return m+':'+s;
}


function renderChart(name, data){

Highcharts.chart('dota-chart', {
   chart:{
      type: 'line'
   },
    title: {
        text: name,
        style: {'font-weight': 'bold', 'font-size': '13px', 'font-family': '"OpenSans-Regular", sans-serif'}
    },

    yAxis: {
        title: {
            text: '',
            style: {'font-weight': 'bold', 'font-size': '12px !important', 'font-family': '"OpenSans-Regular", sans-serif'}
        },
        plotLines: [{
          color: "#81adb6",
          value: 0,
          width: 2,
          zIndex: 10
        }]
    },
    xAxis: {
      tickInterval: 200,
      labels:{
         formatter: function(){
            getDotaTime(this.value);
      },
      style: {'font-weight': 'bold', 'font-size': '12px !important', 'font-family': '"OpenSans-Regular", sans-serif'}
      }
    },


    plotOptions: {
        line: {
            lineWidth: 3,
            marker: {
                enabled: false
            },

        }
    },
    // legend: {
    //     enabled: false,
    //      itemStyle: {'font-family': '"OpenSans-Regular", sans-serif'}

    // },
    legend: {
      enabled: true,
      itemStyle: {'cursor': 'default', 'font-family': 'arial'}
    },
    credits: {
        enabled: false,
    },
    tooltip: {
          formatter: function() {
          var time = getDotaTime(this.x);
          return '<span>Время: '+time+'</span><br/><span>Разница: '+this.y+'</span>';
    },
    shared: false
    },
    series: [{
        name: 'Разница',
        data: data,
        color: '#70d474',
        negativeColor: "#f16f6f",
        showInLegend: false,
    },
    {
        name: radiant_name,
        data: [],
        color: '#70d474',
        events: {
          legendItemClick: function() {
            return false;
          }
        }
    },
    {
        name: dire_name,
        data: [],
        color: "#f16f6f",
        events: {
          legendItemClick: function() {
            return false;
          }
        }
    }]

});
}

var timer = {
   time: 0,
   stop: false,
   setTime: function(time){
      this.time = time;
   },
   tick: function(){
        this.time++;
        var st_time = parseInt(this.time / 60, 10)+':'+parseInt(this.time % 60, 10);
        $('#game_time').text(st_time);
   },

   run: function(){
         if(!timer.stop){
            timer.tick();
            timer.timerRec = setTimeout(timer.run, 1000);    
         }

   },
   runTimer: function(){
      this.stop = false;
      this.run();
   },
   stopTimer: function(){
      clearTimeout(this.timerRec);
      this.stop = true;
   }
}





function renderPlayer($el, player){
      $el.find('.player-img').css('background-image', 'url("/img/heroes/'+player.hero_id+'.png")');
      $el.find('.player-img').attr('hero-id', player.hero_id);

      $el.find('.player-name').removeClass('dire-color').removeClass('radiant-color');

      if(player.team == 1){
         $el.find('.player-name').addClass('dire-color');
      }else{
          $el.find('.player-name').addClass('radiant-color');
      }
      $el.find('.player-name').text(player.name);

      $el.find('.level').text(player.level);
      $el.find('.kills').text(player.kills);
      $el.find('.death').text(player.death);
      $el.find('.assists').text(player.assists);
      $el.find('.gold-a').text(player.gold);
      $el.find('.gpm').text(player.gpm);
      $el.find('.xpm').text(player.xpm);
      $el.find('.net_worth').text(player.net_worth);
      $el.find('.ld').text(player.last_hits+'/'+player.denies);

      $el.find('.dota-player-items .dota-player-item').each(function(g){
          if(g in player.items){
            $(this).css('background-image', 'url("/img/items/'+player.items[g]['item_id']+'.png")');
            $(this).data('item', player.items[g]['item_id']);
            if(player.items[g]['item_id'] != 0){

              $(this).find('.min').remove();
              $(this).append('<span class="min"></span>')
              $(this).find('.min').text(player.items[g]['time']+'m');
            }else{
              $(this).find('.min').remove();
            }           
          }else{
            $(this).css('background-image', 'url("")');
          }

      });

}

function renderPlayerEmpty($el){
      $el.find('.player-img').css('background-image', '');
      $el.find('.player-img').attr('hero-id', '');

      $el.find('.player-name').text('xxxx');

      $el.find('.level').text(0);
      $el.find('.kills').text(0);
      $el.find('.death').text(0);
      $el.find('.assists').text(0);
      $el.find('.gold-a').text(0);
      $el.find('.gpm').text(0);
      $el.find('.xpm').text(0);
      $el.find('.net_worth').text(0);
      $el.find('.ld').text('0/0');

      $el.find('.dota-player-items .dota-player-item').each(function(g){
            $(this).css('background-image', 'url("")');

      });

}
function render(data){
  $('#wait-game').hide();
   if(data.game.active == 0){
      $('#game-update').text('Игра завершилась');
      timer.stopTimer();
   }else if(data.game.game_time > 0){
      timer.stopTimer();
      timer.setTime(data.game.game_time);
      timer.runTimer();
   }

   $('#radiant_score').text(data.game.radiant_score);
   $('#radiant_name').text(data.game.radiant_name);
   radiant_name = data.game.radiant_name;
   $('#radiant_img').css('background-image', 'url("http://www.trackdota.com/data/images/teams/'+data.game.radiant_id+'.png")');

   $('#dire_score').text(data.game.dire_score);
   $('#dire_name').text(data.game.dire_name);
   dire_name = data.game.dire_name;

   $('#dire_img').css('background-image', 'url("http://www.trackdota.com/data/images/teams/'+data.game.dire_id+'.png")');

   var time = parseInt(data.game.game_time / 60, 10)+':'+parseInt(data.game.game_time % 60, 10);
   $('#game_time').text(time);

   $('#radiant_pb .picks .hero-pb-img').each(function(i){
      $(this).css('background-image', 'url("/img/heroes/'+data.radiant.picks[i]+'.png")');
      $(this).attr('hero-id', data.radiant.picks[i]);
   });
   $('#radiant_pb .bans .hero-pb-img').each(function(i){
      $(this).css('background-image', 'url("/img/heroes/'+data.radiant.bans[i]+'.png")');
      $(this).attr('hero-id', data.radiant.bans[i]);
   });
   $('#dire_pb .picks .hero-pb-img').each(function(i){
      $(this).css('background-image', 'url("/img/heroes/'+data.dire.picks[i]+'.png")');
      $(this).attr('hero-id', data.dire.picks[i]);
   });
   $('#dire_pb .bans .hero-pb-img').each(function(i){
      $(this).css('background-image', 'url("/img/heroes/'+data.dire.bans[i]+'.png")');
      $(this).attr('hero-id', data.dire.bans[i]);
   });

   $('.hero-on-map').remove();
   $('.dota-player-item').attr('data-desc', '0');

   $('#dire .player-data').each(function(i){
      var player = data.players.dire[i];
      renderPlayer($(this), player);

        var pos_x = parseInt((parseFloat(player.position_x) + 7500 - 35) / 15000 * 100, 10);

      
        var pos_y = parseInt((parseFloat(player.position_y) + 7500 - 35) / 15000 * 100, 10);

        // var pos_x = Math.round(parseFloat(player.position_x) / 47.1698113 + 159);
        // var pos_y = Math.round(parseFloat(player.position_y) / 47.1698113 + 159);


      $('.dota-map .map').append('<div class="hero-on-map dire" style="left: '+pos_x+'%; bottom: '+pos_y+'%;" psx="'+player.position_x+'" psy="'+player.position_y+'">\
                        <div class="hero-img" style="background-image: url(\'/img/heroes_face/'+player.hero_id+'.png\');"></div>\
                    </div>');

   });

   $('#radiant .player-data').each(function(i){
    var player = data.players.radiant[i];
      renderPlayer($(this), data.players.radiant[i]);

        var pos_x = parseInt((parseFloat(player.position_x) + 7500) / 15000 * 100, 10);

      
        var pos_y = parseInt((parseFloat(player.position_y) + 7500) / 15000 * 100, 10);

        // var pos_x = Math.round(parseFloat(player.position_x) / 47.1698113 + 159);
        // var pos_y = Math.round(parseFloat(player.position_y) / 47.1698113 + 159);

      $('.dota-map .map').append('<div class="hero-on-map radiant" style="left: '+pos_x+'%; bottom: '+pos_y+'%;" psx="'+player.position_x+'" psy="'+player.position_y+'">\
                        <div class="hero-img" style="background-image: url(\'/img/heroes_face/'+player.hero_id+'.png\');"></div>\
                    </div>');

   });
   for(var tower in data.tower){
    if(data.tower[tower] == 1){
      $('#'+tower).removeClass('inactive');
    }else{
    $('#'+tower).addClass('inactive');

    }
   }
    for(var tower in data.rax){
    if(data.rax[tower] == 1){
      $('#'+tower).removeClass('inactive');
    }else{
    $('#'+tower).addClass('inactive');

    }
   }
   $('#gpm_c').attr('value', JSON.stringify(data.shedule[0]));
   $('#xpm_c').attr('value', JSON.stringify(data.shedule[2]));
   $('#gold_c').attr('value', JSON.stringify(data.shedule[1]));
   $('#net_worth_c').attr('value', JSON.stringify(data.shedule[3]));
   $('#xp_c').attr('value', JSON.stringify(data.shedule[4]));

   var textA = $('.selector-chart .active').text().toLowerCase();

   renderChart(textA, data.shedule[$('.selector-chart .active').attr('type')]);
}


function renderEmpty(){
    if($('.over').text() != 'Live'){
      $('#wait-game').show();
    }
    
    $('#game-update').text('Игра неактивная');
    timer.stopTimer();

   $('#radiant_score').text(0);
   $('#radiant_name').text('xxxx');
   radiant_name = 'xxxx';
   $('#radiant_img').css('background-image', '');

   $('#dire_score').text(0);
   $('#dire_name').text('xxxx');
   dire_name = 'xxxx';

   $('#dire_img').css('background-image', '');

   $('#radiant_pb .picks .hero-pb-img').each(function(i){
      $(this).css('background-image', '');
      $(this).attr('hero-id', '');
   });
   $('#radiant_pb .bans .hero-pb-img').each(function(i){
      $(this).css('background-image', '');
      $(this).attr('hero-id', '');
   });
   $('#dire_pb .picks .hero-pb-img').each(function(i){
      $(this).css('background-image', '');
      $(this).attr('hero-id', '');
   });
   $('#dire_pb .bans .hero-pb-img').each(function(i){
      $(this).css('background-image', '');
      $(this).attr('hero-id', '');
   });

   $('.hero-on-map').remove();
   $('.dota-player-item').attr('data-desc', '0');

   $('#dire .player-data').each(function(i){
      renderPlayerEmpty($(this));
   });

   $('#radiant .player-data').each(function(i){
      renderPlayerEmpty($(this));

   });

   $('#gpm_c').attr('value', '');
   $('#xpm_c').attr('value', '');
   $('#gold_c').attr('value', '');
   $('#net_worth_c').attr('value', '');
   $('#xp_c').attr('value', '');

   var textA = $('.selector-chart .active').text().toLowerCase();

   renderChart(textA, []);
}


var games = {};
var open_match;



function updateEvent(){
  var now_games = [];
  $('.selecter-dota').each(function(){
      now_games.push($(this).attr('match-id'));
  });
      $.ajax({
         url: "/get-new-games",
         type: "GET",
         data: {'games': now_games, 'match_id': match_id},
         dataType: "json",
         success: function(data){
          if(data.new > 0){
            match_over = data.over;
            if(data.over == 1){
              $('.over').text('Матч завершился');
            }
            $('.dota-game-select .active').removeClass('active');
            $('[active="1"]').attr('active', 0);
            $('[match-id="-1"]').addClass('active');
            $('[match-id="-1"]').attr('active', 1);
            $('[match-id="-1"]').attr('match-id', data.match_id);
            open_match = data.match_id;
            if(data.match_id in games){
              render(games[data.match_id]);
            }else{
              getOverMatch(data.match_id);
            }

            liveGame(data.match_id);

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
         }
      });

}

var updt = setInterval(function(){
  if(match_over == 0){
    if($('[active="1"]').length == 0){
      updateEvent();
    }
  }else{
    clearInterval(updt);
}
  
}, 3 * 60 * 1000);


function tickUpdate(match_id, time){
   if(open_match == match_id){
      $('#game-update').text('Обновление через '+time);
   }
}

function liveGame(match_id){
      $.ajax({
         url: "/get-game",
         type: "GET",
         data: {'match_id': match_id},
         dataType: "json",
         success: function(data){
            if(data.game.active == 0){
              games[match_id] = data;
               timer.stopTimer();
               var l = $('.dota-game-select .selecter-dota').length;
               $('[active="1"]').attr('active', 0);
               $('.dota-game-select .active').removeClass('active');
               $('.dota-game-select').append('<div class="selecter-dota active" match-id="-1">Игра '+(l + 1)+'</div>');

               renderEmpty();

            }else{
               var t = 20;
               var timeInt = setInterval(function(){
                  tickUpdate(match_id, t);
                  t--;
               }, 1000);
               setTimeout(function(){
                  clearInterval(timeInt);
                  if(open_match == match_id){
                    $('#game-update').text('Обновление..');
                  }
                  liveGame(match_id);
               }, 20000);
               games[match_id] = data;
               if(open_match == match_id){
                  render(data);
               }
               
            }

         }
      
      });   
}


function getOverMatch(match_id){
      $.ajax({
         url: "/get-game",
         type: "GET",
         data: {'match_id': match_id},
         dataType: "json",
         success: function(data){
            games[match_id] = data;
            render(data);

         }
      
      });  
}



$(document).ready(function(){

   $('.dota-players .dota-players-table th[sort-type]').hover(function(){
      var sort_type = $(this).attr('sort-type'); 
      var className = sort_type == 1 ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up';
      $('<i class="sort_dota glyphicon '+className+'"></i>').appendTo(this).animate({'opacity': '1'}, 300);
   }, function(){
      var s = $(this).find('.sort_dota');
      s.stop().animate({'opacity': '0'}, 300, function(){
         s.remove();
      })
   });

   $('.dota-players .dota-players-table th[sort-type]').click(function(){
      var sort_type = $(this).attr('sort-type'); 
      var index = $(this).index();
      
      $(this).closest('.dota-players-table').find('tbody tr').sort(function(a, b) { // сортируем
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
      }).appendTo($(this).closest('.dota-players-table').find('tbody'));
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
$('.dota-chart .dota-game-chart .selector-chart .chart-item').click(function(){
   $('.dota-chart .dota-game-chart .selector-chart .active').removeClass('active');
   $(this).addClass('active');
   var chartText = $(this).text();
   var chartData = JSON.parse($(this).attr('value'));

   renderChart(chartText, chartData);
});




$(document).on('click', '.dota-game-select .selecter-dota', function(){
   open_match = $(this).attr('match-id');
   if(open_match != -1){
     if(open_match in games){
        render(games[open_match]);
     }else{
        getOverMatch(open_match);
     }
 }else{
    renderEmpty();
 }
   $('.dota-game-select .active').removeClass('active');
   $(this).addClass('active');

});

open_match = $('.dota-game-select .active').attr('match-id');
if($('.dota-game-select .active').attr('active') == 1){
   liveGame(open_match);
}
var open = false;
$('#expand').click(function(){
   if(!open){
      $('.game-holder').css('height', 'auto');
      open = true;
      $('#expand').text('Закрыть');

   }else{
      $('.game-holder').css('height', '0px');
      open = false;
      $('#expand').text('Открыть');
   }
});

if($('.dota-game-select .active').attr('match-id') == -1){
  renderEmpty();
}

});


var chartText = $('.dota-chart .dota-game-chart .selector-chart .active').text();
var chartData = $('.dota-chart .dota-game-chart .selector-chart .active').attr('value');

//console.log(chartData);

renderChart(chartText, JSON.parse(chartData));


function itemTooltip(data){
  $('#item-tooltip .item-img').css('background-image', 'url("/img/items/'+data.id+'.png")');
  $('#item-tooltip .item-name').text(data.dname);
  $('#item-tooltip .item-coast div').text(data.cost);

  $('#item-tooltip .desc-s').html(data.desc);
  $('#item-tooltip .item-note').html(data.notes);
  $('#item-tooltip .item-top').html(data.attrib);
  $('#item-tooltip .lore').html(data.lore);

}

function heroTooltip(data){
  $('#hero-tooltip .hero-img').css('background-image', 'url(\'/img/heroes/'+data.id+'.png\')');
  $('#hero-tooltip .hero-name').text(data.name);
  $('#hero-tooltip .hero-roles').text(data.roles);

  $('#hero-tooltip .hero-skills').empty();

  JSON.parse(data.skills).forEach(function(item, i){
    if(i < 4){
    $('#hero-tooltip .hero-skills').append('<div class="hero-skill">\
        <div class="skill-img" style="background-image: url(\'http://cdn.dota2.com/apps/dota2/images/abilities/'+item.id+'_hp1.png\')"></div>\
        <div class="skill-about">\
          <span class="skill-name">'+item.dname+'</span>\
          <p class="skill-over">'+item.desc+'</p>\
        </div>\
      </div>');

  }else return false;
 }); 



}

var items_data = {};
var heroes_data = {};

$.get("/items-data", {}, function(data){
  items_data = data;
}, 'JSON');


$.get("/heroes-data", {}, function(data){
  heroes_data = data;
  console.log(data);
}, 'JSON');

$(document).on('mouseenter','.dota-player-item', function(){

    //console.log($(this).data('desc'));
    var item_id = $(this).data('item');
    if(item_id in items_data){
      itemTooltip(JSON.parse(items_data[item_id]['description']));
      $('#item-tooltip').css('top', $(this).offset().top);
      $('#item-tooltip').css('left', $(this).offset().left + $(this).width() + 20);
      $('#item-tooltip').show();     
    }

});

$(document).on('mouseleave','.dota-player-item', function(){
      $('#item-tooltip').hide();    
});


$(document).on('mouseenter', '.player-img', function(){
    var skills = $(this).data('skills');
    var data = $(this).data('hero');
    console.log(data);
    if(data && skills){
      heroTooltip(data, skills);
      $('#hero-tooltip').css('top', $(this).offset().top);
      $('#hero-tooltip').css('left', $(this).offset().left + $(this).width() + 20);
      $('#hero-tooltip').show();     
    }

});
$(document).on('mouseleave','.player-img', function(){
      $('#hero-tooltip').hide();    
});


$(document).on('mouseenter', 'div[hero-id]', function(){

    var id = $(this).attr('hero-id');
    if(id in heroes_data){
      heroTooltip(heroes_data[id]);
      $('#hero-tooltip').css('top', $(this).offset().top);
      $('#hero-tooltip').css('left', $(this).offset().left + $(this).width() + 20);
      $('#hero-tooltip').show();     
    }

});
$(document).on('mouseleave','div[hero-id]', function(){
      $('#hero-tooltip').hide();    
});

// var a = [{x: 0, y: 0}, {x: 0, y: 7500}, {x: 0, y: 3750}, {x: 0, y: -3750}, {x: 0, y: -7500}, {y: 0, x: 7500}, {y: 0, x: 3750}, {y: 0, x: -3750}, {y: 0, x: -7500}, {y: 7500, x: 7500}, 
// {y: 3750, x: 3750}, {y: -7500, x: 7500}, 
// {y: -3750, x: 3750}, {y: 7500, x: -7500}, 
// {y: 3750, x: -3750}, {y: -7500, x: -7500}, 
// {y: -3750, x: -3750}];

// a.forEach(function(item){
//         const x = 47.1698113;
//         var pos_x = Math.round(item.x / x + 159);
//         var pos_y = Math.round(item.y / x + 159);
//   $('.dota-map .map').append('<div style="position: absolute; width: 10px; height: 10px; margin-left: -5px; margin-bottom: -5px; left: '+pos_x+'px; bottom: '+pos_y+'px; background-color: #fff;"></div>');
// });
