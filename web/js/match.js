'use strict';
/* global document */
// Load the fonts
Highcharts.createElement('link', {
   href: 'https://fonts.googleapis.com/css?family=Unica+One',
   rel: 'stylesheet',
   type: 'text/css'
}, null, document.getElementsByTagName('head')[0]);

Highcharts.theme = {
   colors: ['#2b908f', '#90ee7e', '#f45b5b', '#7798BF', '#aaeeee', '#ff0066', '#eeaaee',
      '#55BF3B', '#DF5353', '#7798BF', '#aaeeee'],
   chart: {
      backgroundColor: {
         linearGradient: { x1: 0, y1: 0, x2: 1, y2: 1 },
         stops: [
            [0, '#121718']
         ]
      },
      style: {
         fontFamily: '\'Unica One\', sans-serif'
      },
      plotBorderColor: '#606063'
   },
   title: {
      style: {
         color: '#E0E0E3',
         textTransform: 'uppercase',
         fontSize: '20px'
      }
   },
   subtitle: {
      style: {
         color: '#E0E0E3',
         textTransform: 'uppercase'
      }
   },
   xAxis: {
      gridLineColor: '#707073',
      labels: {
         style: {
            color: '#E0E0E3'
         }
      },
      lineColor: '#273538',
      minorGridLineColor: '#505053',
      tickColor: '#273538',
      title: {
         style: {
            color: '#A0A0A3'

         }
      }
   },
   yAxis: {
      gridLineColor: '#273538',
      labels: {
         style: {
            color: '#E0E0E3'
         }
      },
      lineColor: '#273538',
      minorGridLineColor: '#505053',
      tickColor: '#273538',
      tickWidth: 1,
      title: {
         style: {
            color: '#A0A0A3'
         }
      }
   },
   tooltip: {
      backgroundColor: 'rgba(0, 0, 0, 0.85)',
      style: {
         color: '#F0F0F0'
      }
   },
   plotOptions: {
      series: {
         dataLabels: {
            color: '#B0B0B3'
         },
         marker: {
            lineColor: '#333'
         }
      },
      boxplot: {
         fillColor: '#505053'
      },
      candlestick: {
         lineColor: 'white'
      },
      errorbar: {
         color: 'white'
      }
   },
   legend: {
      itemStyle: {
         color: '#E0E0E3'
      },
      itemHoverStyle: {
         color: '#FFF'
      },
      itemHiddenStyle: {
         color: '#606063'
      }
   },
   credits: {
      style: {
         color: '#666'
      }
   },
   labels: {
      style: {
         color: '#707073'
      }
   },

   drilldown: {
      activeAxisLabelStyle: {
         color: '#F0F0F3'
      },
      activeDataLabelStyle: {
         color: '#F0F0F3'
      }
   },

   navigation: {
      buttonOptions: {
         symbolStroke: '#DDDDDD',
         theme: {
            fill: '#505053'
         }
      }
   },

   // scroll charts
   rangeSelector: {
      buttonTheme: {
         fill: '#505053',
         stroke: '#000000',
         style: {
            color: '#CCC'
         },
         states: {
            hover: {
               fill: '#707073',
               stroke: '#000000',
               style: {
                  color: 'white'
               }
            },
            select: {
               fill: '#000003',
               stroke: '#000000',
               style: {
                  color: 'white'
               }
            }
         }
      },
      inputBoxBorderColor: '#505053',
      inputStyle: {
         backgroundColor: '#333',
         color: 'silver'
      },
      labelStyle: {
         color: 'silver'
      }
   },

   navigator: {
      handles: {
         backgroundColor: '#666',
         borderColor: '#AAA'
      },
      outlineColor: '#CCC',
      maskFill: 'rgba(255,255,255,0.1)',
      series: {
         color: '#7798BF',
         lineColor: '#A6C7ED'
      },
      xAxis: {
         gridLineColor: '#505053'
      }
   },

   scrollbar: {
      barBackgroundColor: '#808083',
      barBorderColor: '#808083',
      buttonArrowColor: '#CCC',
      buttonBackgroundColor: '#606063',
      buttonBorderColor: '#606063',
      rifleColor: '#FFF',
      trackBackgroundColor: '#404043',
      trackBorderColor: '#404043'
   },

   // special colors for some of the
   legendBackgroundColor: 'rgba(0, 0, 0, 0.5)',
   background2: '#505053',
   dataLabelsColor: '#B0B0B3',
   textColor: '#C0C0C0',
   contrastTextColor: '#F0F0F3',
   maskColor: 'rgba(255,255,255,0.3)'
};

// Apply the theme
Highcharts.setOptions(Highcharts.theme);

function renderBk($el){
var name = $el.text();
var ks = JSON.parse($el.attr('d'));

for(var i = 0; i < ks['k1'].length; i++){
  ks['k1'][i][1] = parseFloat(ks['k1'][i][1]);
  ks['k1'][i][0] = parseInt(ks['k1'][i][0], 10);
}

for(var i = 0; i < ks['k2'].length; i++){
  ks['k2'][i][1] = parseFloat(ks['k2'][i][1]);
  ks['k2'][i][0] = parseInt(ks['k2'][i][0], 10);
}

Highcharts.chart('bk-chart', {
   chart:{
      type: 'spline'
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
    },
    credits: {
      enabled: false
    },
    xAxis: {
      labels:{
         formatter: function(){
          return '';
           return parseInt(this.value / 100, 10)+':'+parseInt(this.value % 100, 10);
      },
      style: {'font-weight': 'bold', 'font-size': '12px !important', 'font-family': '"OpenSans-Regular", sans-serif'}
      }
    },


    plotOptions: {
        spline: {
            lineWidth: 4,
            states: {
                hover: {
                    lineWidth: 5
                }
            },
            marker: {
                enabled: false
            },

        }
    },
    legend: {
         itemStyle: {'font-family': '"OpenSans-Regular", sans-serif'}

    },
    tooltip: {
          formatter: function() {
          var date = new Date(this.x * 1000);
          var hours = date.getHours();
          var minutes = date.getMinutes();
          if(hours < 10){
            hours = '0'+hours;
          }
          if(minutes < 10){
            minutes = '0'+minutes;
          }
          return '<span>Время: '+hours+':'+minutes+'</span><br/><span>Коэффициент: '+this.y+'</span>';
    }
  },
    series: [{
        name: team1_name,
        data: ks['k1'],
        color: '#3ab6ce'
    }, {
        name: team2_name,
        data: ks['k2'],
        color: '#f16f6f'
    }]

});
}

function updateBk(){
      $.ajax({ 
        type: 'POST', 
        url: '/get-bk-n', 
        data: {'match_id': match_id},
        dataType: 'JSON',
        success: function (data) { 
            for(var key in data){
              $('.bk-graph-'+key).attr('d', JSON.stringify(data[key]));
              $('#koff-'+key).find('.odd-num').first().text(data[key]['k1'][data[key]['k1'].length - 1][1]);
              $('#koff-'+key).find('.odd-num').last().text(data[key]['k2'][data[key]['k2'].length - 1][1]);
            }
            renderBk($('.active-x'));
            timetoBk(10 * 60);
        }

    });

  }

function timetoBk(time_start){
  var time = time_start;
  var int = setInterval(function(){
    time--;
    var min = parseInt(time / 60, 10);
    var sec = parseInt(time % 60, 10);
    if(min < 10){
      min = '0'+min;
    }
    if(sec < 10){
      sec = '0'+sec;
    }
    if(time == 0){
      clearInterval(int);
      updateBk();
    }
    $('#bk-timer').text('Обновление через '+min+':'+sec);
  }, 1000);
}

timetoBk($('#bk-timer').attr('time'));

var bk1 = $('.bk-button').first();
if(bk1.length > 0){
  renderBk($('.bk-button').first());
}

$(document).ready(function() {

$('.dota-chart .dota-game-chart .selector-chart .bk-button').click(function(){
   $('.dota-chart .dota-game-chart .selector-chart .active-x').removeClass('active-x');
   $(this).addClass('active-x');
   renderBk($(this));
});
	$('#chats').click(function(){
   $('#first_a').addClass('active in');
   $('#chat_a').removeClass('active in');
});
$('#chat').click(function(){
   $('#first_a').removeClass('active in');
   $('#chat_a').addClass('active in');
});

$('.new-stream').click(function(){
	var type = $(this).data('type');
	if(type == 0){
		var channel = $(this).data('stream');
  		$('#chat_embed').attr('src', 'https://www.twitch.tv/'+channel+'/chat');
  		$('#stream_embed').attr('src', 'https://player.twitch.tv/?channel='+channel);
	}else{
		var channel = $(this).data('video');
  		$('#chat_embed').attr('src', '');
  		$('#stream_embed').attr('src', channel);		
	}
  	

});


function updateStreams(){
   var selected_stream = $('.new-stream').first().data('stream');
   $.ajax({
     url: "/get-match-streams",
     type: "GET",
     data: {'match_id': match_id},
     dataType: "json",
     success: function(data){
      console.log(data);
        if(data.length > 0){
          $('.new-stream').remove();
          data.forEach(function(item){
            $('.scroll-stream').append('<div class="striem-el clearfix new-stream" data-type="0" data-stream="'+item.channel+'" >\
                              <img src="/img/flags/'+item.country+'.png" onerror="this.src=\'/img/flags/eflag.png\'">\
                              <div class="striem-info">\
                                  <span>'+item.channel+' ('+item.country.toUpperCase()+')</span>\
                              </div>\
                          </div> ');
          if($('div').is('.wait-stream')){
            $('.wait-stream').replaceWith('<div class="stream-video">\
                <div class="iframe">\
                  <iframe\
                        id="stream_embed"\
                        autoplay="0"\
                        src="https://player.twitch.tv/?channel='+data[0].channel+'"\
                        frameborder="0"\
                        scrolling="no"\
                        allowfullscreen="true">\
                </iframe>\
                </div>\
        </div>');
            $('#chat_a').append('<iframe frameborder="0"\
                    scrolling="no"\
                    id="chat_embed"\
                    src="https://www.twitch.tv/'+data[0]['channel']+'/chat">\
                </iframe>');
          }else{
            if(data[0].channel != selected_stream){
              $('#chat_embed').attr('src', 'https://www.twitch.tv/'+data[0].channel+'/chat');
              $('#stream_embed').attr('src', 'https://player.twitch.tv/?channel='+data[0].channel);
          }
          }
          });
      }
     }
   });

}

var updt_stream = setInterval(function(){
  if(match_over == 0){
    updateStreams();
  }else{
    clearInterval(updt_stream);
}
  
}, 0.5 * 60 * 1000);

});