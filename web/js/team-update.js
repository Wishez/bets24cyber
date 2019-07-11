$(document).ready(function () {
// var start = 0;
//         $(document).ajaxStart(function () {
//             $('.load').addClass('reload');
//             start = 1;

//         });
//         $(document).ajaxStop(function () {
//             $('.load').removeClass('reload');
//             start = 0;
//         });



// function get(name){
//   switch(name){
//     case 'teamscsgo':
//     var url = '/updatecsgo';
//     break;

//     case 'teamsdota2':
//     var url = '/updatedota2';
//     break;

//     case 'playerscsgo':
//     var url = '/updateplayerscsgo';
//     break;

//     case 'playersdota2':
//     var url = '/updateplayersdota2';
//     break;
//   }
//         $.ajax({
//             url: url,
//             success: function (data) {
//                alert('Успешно обновлено');
//             }
//         });

// }
// $('#teamscsgo').on('click', function () {
//     if(start == 0){
// get('teamscsgo');
// }
// });

// $('#teamsdota2').on('click', function () {
//     if(start == 0){
// get('teamsdota2');
// }
// });
// $('#playerscsgo').on('click', function () {
//     if(start == 0){
// get('playerscsgo');
// }
// });

// $('#playersdota2').on('click', function () {
//     if(start == 0){
// get('playersdota2');
// }
// });
var update = false;
        $(document).ajaxStart(function () {
            $('.load').addClass('reload');
            update = true;

        });
        $(document).ajaxStop(function () {
            $('.load').removeClass('reload');
            update = false;
        });
function log(text, type){
    var color;
    if(type == 'err') color = '#ec3838';
    if(type == 'success') color = '#32cc29';
    $('#log').append('<span style="color: '+color+'">'+text+'</span>');
}

var teama;
var i = 0;
function updateTeam(team){
    $.ajax({
        url: "/league/team",
        method: 'GET',
        data: team,
        timeout: 1000 * 1000,
        success: function (data){
            log('Команда '+team.team+' обновлена', 'success');
             i++;
            if(i < teama.length){
               
                updateTeam({game: teama[i].game, team: teama[i].team});
                
            }
        },
        error: function(){
            log('Ошибка при обновлении '+team.team, 'err');
        }
    });   
}

function updateTeams(){
    $.ajax({
        url: "/league/team-list",
        timeout: 1000 * 1000,
        success: function (data){
            data = JSON.parse(data);
            log('Получено '+data.length+' команды', 'success');
            log('Начинаем обновление', '');
            teama = data;
            updateTeam({game: teama[i].game, team: teama[i].team});

        },
        error: function(){
            log('Ошибка при загрузке команд', 'err');
        }
    });
}

var leagues;
var index = 0;

function updateLeague(league){
    $.ajax({
        url: "/league/update-league",
        data: league,
        method: 'GET',
        timeout: 1000 * 1000,
        success: function (data){
            log('Лига '+league.league+' обновлена', 'success');
            index++;
            if(index < leagues.length){

                updateLeague({game: leagues[index].game, league: leagues[index].league});
                
            }
        },
        error: function(){
            log('Ошибка при загрузке лиг', 'err');
        }
    });
}
function updateLeagues(){
    $.ajax({
        url: "/league/league-list",
        success: function (data){
            data = JSON.parse(data);
            log('Получено '+data.length+' лиги', 'success');
            leagues = data;
            updateLeague({game: data[index].game, league: data[index].league});
            
        },
        error: function(){
            log('Ошибка при загрузке лиг', 'err');
        }
    });
}



$('#team-update').click(function(){
    updateTeams();
});
$('#league-update').click(function(){
    updateLeagues();
});
});

