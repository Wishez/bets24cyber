<?php

use app\models\Bookmaker;
use app\models\Favorites;
use app\models\League;
use app\models\DotaGame;
use app\models\Match;
use app\models\LoadData;
use app\models\Team;
//use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\db\Query;

/* @var $this yii\web\View */
/* @var $model app\models\League */
/* @var $form ActiveForm */
$this->params['class_page'] = 'stream-body';

$this->params['breadcrumbs'][] = ['label' => 'Матчи', 'url' => ['/match-info']];


preg_match('/^(.+?)\//', $model['league'], $n);
$league = empty($model['league']) ? $n[0] : $model['league'];

if($model['event'] == 1){

    preg_match('/^(.+?)\//', $model['main']['name'], $n);
    $model['main']['name'] = empty($model['league']) ? $n[0] : $model['main']['name'];

    $this->params['breadcrumbs'][] = ['label' => $model['main']['name'], 'url' => ['/league-details?id='.$model['main']['id']]];


    $this->params['breadcrumbs'][] = ['label' => $model['main']['name_a'], 'url' => ['/league-details?id='.$model['l_id']]];
}else{
    $this->params['breadcrumbs'][] = ['label' => $league, 'url' => ['/league-details?id='.$model['l_id']]];
}




$team1 = empty($model['team1_name']) ? $model['team1_id'] : $model['team1_name'];
$team2 = empty($model['team2_name']) ? $model['team2_id'] : $model['team2_name'];
$this->params['breadcrumbs'][] =  $team1.' vs '.$team2;



$this->title = $league.' | '.$team1.' vs '.$team2;
$this->description = $league.' | '.$team1.' vs '.$team2.' , узнать составы команд и историю их встреч. Посмотреть полную историю встреч.';

$this->registerJsFile('/js/addFavorites.js', ['depends' => ['yii\web\JqueryAsset', 'yii\jui\JuiAsset']]);
$this->registerJsFile('//code.highcharts.com/highcharts.js');

$this->registerJsFile('/js/m.js', ['depends' => ['yii\web\JqueryAsset', 'yii\jui\JuiAsset']]);





?>

<div class="hr hr-match"></div>
<div class="site-match-details">
    <div class="top-descr">
        <h2><?= $league ?></h2>
        <h3><?= date('d.m.Y H:i', strtotime($model['date'])) ?></h3>
    </div>

    <div class="counter clearfix">
        <div class="col-md-4 col-sm-4 col-sm-push-4 col-xs-12 fl-clock">
            <div class="wrap-clock">
            <?php if($model['over']){ ?>
            <div class="over">Матч завершился</div>
            <div class="score"><?= $model['score'] ?></div>
            <?php }else if(strtotime($model['date']) < time()){ ?>
                <div class="over">Live</div>
                <div class="score"><?= $model['score'] ?></div>
            <?php }else{  ?>
                <div class="over">Матч начнется</div>
                <div class="clock-back" data-time="<?= strtotime($model['date']) - time() ?>"></div>
                <script type="text/javascript">
                 	var timeLeft = $('.clock-back').data('time');
                 	setInterval(function(){
                 		timeLeft--;
                 		var hours = Math.floor(timeLeft / 3600);
                 		var minutes = Math.floor(timeLeft / 60 % 60);
                 		var seconds = Math.floor(timeLeft % 60);


                 		$('.clock-back').text(hours+' ч. '+minutes+' м. '+seconds+' с.');
                 	}, 1000);
			    </script>
                <?php }?>
            </div>
        <?php if($model['game'] == 0){ 
                $dota_games = (new Query())->select('match_id, active')
                    ->from('dota_games')->where(['team1' => $model['team1_id'], 'team2' => $model['team2_id'], 'date' => $model['date']])->orderBy('active')->all();
                if(!empty($dota_games)){
                    $last = $dota_games[count($dota_games) - 1]['active'] == 1 ? true : false;
                    $match_id = $dota_games[count($dota_games) - 1]['active'] == 1 ? $dota_games[count($dota_games) - 1]['match_id'] : $dota_games[0]['match_id'];
                    $game_data = DotaGame::GetGame($match_id);

        ?>
            <div class="dota-game-select">
                <?php for($i = 0; $i < count($dota_games); $i++){ ?>
                    <?php 
                        $selected = false;
                        if($i == (count($dota_games) - 1)){
                            $selected = $last ? true : false;
                        }else if($i == 0){
                            $selected = !$last ? true : false;
                        }
                        

                    ?>
                    <div class="selecter-dota <?= $selected ? 'active' : '' ?>" match-id="<?= $dota_games[$i]['match_id'] ?>" active="<?= $dota_games[$i]['active'] ?>">Игра <?= $i + 1 ?></div>
                <?php } ?>
                </div>
        <?php } } ?>

</div>

        <div class="col-md-4 col-sm-4 col-sm-pull-4 col-xs-12">

            <div class="col-md-6 col-sm-12 col-xs-7" style="float: right;">
                <div class="wram-list-img">
                    <div class="team-blocks">
                        <div class="team-logo" style="background-image: url('<?= !empty($model['team1_logo']) ? $model['team1_logo'] : '/img/dota2.png' ?>')"></div>
                        <div class="team-name"><?= $team1 ?></div>
                    </div>
                </div>
            </div>
                        <div class="col-md-6 col-sm-12 col-xs-5 players-of-team-c" style="float: left;">
                <div class="players-of-team">
                <?php foreach ($team1_players as $value) { ?>
                    <div class="player-r">
                        <div class="nick" style="text-align: right; margin-right: 6px;"><a href="/player-details?id=<?= $value['id'] ?>"><?= $value['player_id'] ?></a></div>
                        <div class="flag" style="background-image: url('/img/flags/<?= !empty($value['country']) ? Match::country($value['country']) : 'eu' ?>.png');"></div>
                        
                    </div>
                <?php }  ?>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-4 col-sm-4 col-xs-12">
            <div class="col-md-6 col-sm-12 col-xs-7">
                <div class="wram-list-img">
                    <div class="team-blocks">
                        <div class="team-logo" style="background-image: url('<?= !empty($model['team2_logo']) ? $model['team2_logo'] : '/img/dota2.png' ?>')"></div>
                        <div class="team-name"><?= $team2 ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-5 players-of-team-c">
                <div class="players-of-team">
                <?php foreach ($team2_players as $value) { ?>
                    <div class="player-r">
                        <div class="flag" style="background-image: url('/img/flags/<?= !empty($value['country']) ? Match::country($value['country']) : 'eu' ?>.png')"></div>
                        <div class="nick"><a href="/player-details?id=<?= $value['id'] ?>"><?= $value['player_id'] ?></a></div>
                    </div>
                <?php }  ?>
                </div>
            </div>
        </div>

<?php
$heroes_a = Match::find()->select('*')->from('heroes')->asArray()->all();
$heroes = json_decode($heroes['data']);
if(count($heroes) > 0){
   if($model['game'] == 1){ ?>
        <div class="col-md-12 col-sm-12 col-xs-12">
             <?php for ($i = 0; $i < count($heroes); $i++) { ?>
             <div class="csgo-con">
             <div class="st-csgo-row">
                <span class="team-score"><?= $heroes[$i]->left ?></span>
                <span class="team-map"><?= $heroes[$i]->map ?></span>
                <span class="team-score"><?= $heroes[$i]->right ?></span>
             </div>
             </div>
             <?php } ?>
        </div>
         <?php  }} ?>
    </div>
    <!-- ВВЕРХ -->

                <?php
                    // $stream = [];
                    // $frames = explode(', ', $model['stream_m']);
                    // $chats = explode(', ', $model['chat_m']);
                    // $frames2 = explode(', ', $model['stream']);
                    // $chats2 = explode(', ', $model['chat']);
                    // for ($i = 0; $i < count($frames); $i++) { 
                    //     if(empty($chats[$i])){
                    //         $ch = empty($chats[0]) ? '' : $chats[0];
                    //     }else{
                    //         $ch = $chats[$i];
                    //     }
                    //     if(!empty($frames[$i])){
                    //     $a = ['frame' => $frames[$i], 'chat' => $ch];
                    //     array_push($stream, $a);
                    // }
                    // }
                    // for ($i = 0; $i < count($frames2); $i++) { 
                    //     if(empty($chats2[$i])){
                    //         $ch = empty($chats2[0]) ? '' : $chats2[0];
                    //     }else{
                    //         $ch = $chats2[$i];
                    //     }
                    //     if(!empty($frames2[$i])){
                    //     $a = ['frame' => $frames2[$i], 'chat' => $ch];
                    //     array_push($stream, $a);
                    // }
                    // }

                ?>
<?php 
if($model['game'] == 0){

    if(!isset($game_data));

    $time = ((int)($game_data['game']['game_time'] / 60)).':'.((int)($game_data['game']['game_time'] % 60));

?>
<div class="col-md-12 col-sm-12 col-sm-12 col-xs-12 ">
    <div class="dota-game-nav-panel">
        <div class="col-md-12">
            <div class="col-md-5 col-xs-12 team-b">
                <div class="team-score-b dire-b"><span id="radiant_score"><?= $game_data['game']['radiant_score'] ?></span></div>
                <div class="team-img-b dire-b" id="radiant_img" style="background-image: url('http://www.trackdota.com/data/images/teams/<?= $game_data['game']['radiant_id'] ?>.png')"></div>
                <div class="team-block-b dire-block dire-b">
                    <span class="team-name-b dire-name" id="radiant_name"><?= $game_data['game']['radiant_name'] ?></span>
                    <span class="type">Силы света</span>
                </div>

            </div>
            <div class="col-md-2 col-xs-12 time-b">
                <span class="timer" id="game_time"><?= $time ?></span>
            </div>
            <div class="col-md-5 col-xs-12 team-b" style="margin-bottom: 10px;">
                <div class="team-score-b radiant-b"><span id="dire_score"><?= $game_data['game']['dire_score'] ?></span></div>
                <div class="team-img-b radiant-b" id="dire_img" style="background-image: url('http://www.trackdota.com/data/images/teams/<?= $game_data['game']['dire_id'] ?>.png')"></div>
                <div class="team-block-b radiant-block radiant-b">
                    <span class="team-name-b radiant-name" id="dire_name"><?= $game_data['game']['dire_name'] ?></span>
                    <span class="type">Силы тьмы</span>
                </div>
            </div>
        </div>
    </div>
    <div class="expand" id="expand">
        <span>Открыть</span>
    </div>

</div>
<div class="col-md-12 col-sm-12 col-xs-12 game-holder">
    <div class="main-dota-game-info">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="update-game" id="game-update"><?= $game_data['game']['active'] == 0 ? 'Игра завершилась' : 'Обновление через 20' ?></div>
        </div>
        <div class="dota-pick-bans col-md-12 col-sm-12 col-xs-12">
            <div class="radiant-pb pb-dota col-md-6 col-xs-12" id="radiant_pb">
                <div class="picks pb-block">
                    <?php for ($i = 0; $i < 5; $i++) {  ?>
                    <div class="hero-pb-img" style="background-image: url('http://www.trackdota.com/static/heroes/jpg/128/<?= $game_data['radiant']['picks'][$i] ?>.jpg'); ">
                        <span class="type-pb">Пик <?= $i + 1?></span>
                    </div>
                    <?php } ?>
                </div>
                <div class="bans pb-block">
                    <?php for ($i = 0; $i < 5; $i++) {  ?>
                    <div class="hero-pb-img" style="background-image: url('http://www.trackdota.com/static/heroes/jpg/128/<?= $game_data['radiant']['bans'][$i] ?>.jpg'); ">
                        <span class="type-pb">Бан <?= $i + 1?></span>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <div class="radiant-pb pb-dota col-md-6 col-xs-12" id="dire_pb">
                <div class="picks pb-block" >
                    <?php for ($i = 0; $i < 5; $i++) {  ?>
                    <div class="hero-pb-img" style="background-image: url('http://www.trackdota.com/static/heroes/jpg/128/<?= $game_data['dire']['picks'][$i] ?>.jpg'); ">
                        <span class="type-pb">Пик <?= $i + 1?></span>
                    </div>
                    <?php } ?>
                </div>
                <div class="bans pb-block">
                    <?php for ($i = 0; $i < 5; $i++) {  ?>
                    <div class="hero-pb-img" style="background-image: url('http://www.trackdota.com/static/heroes/jpg/128/<?= $game_data['dire']['bans'][$i] ?>.jpg'); ">
                        <span class="type-pb">Бан <?= $i + 1?></span>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="dota-players col-md-12 col-sm-12 col-xs-12">
            <div class="scroller">
            <table class="dota-players-table">
                <thead>
                <tr>
                    <th class="player-head">Игрок</th>
                    <th sort-type='1'>LVL</th>
                    <th sort-type='0'>K</th>
                    <th sort-type='0'>D</th>
                    <th sort-type='0'>A</th>
                    <th class="gold" sort-type='0'>Gold</th>
                    <th sort-type='0'>LH/DH</th>
                    <th class="gold" sort-type='0'>GPM</th>
                    <th sort-type='0'>XPM</th>
                    <th sort-type='0'>Net</th>
                    <th class="player-items">Предметы</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($game_data['players'] as $player) { ?>
                <tr class="player-data">
                    <td class="player-body">
                        <div class="player-img" style="background-image: url('http://www.trackdota.com/static/heroes/jpg/128/<?= $player['hero_id'] ?>.jpg'); "></div>
                        <a href=""><div class="player-name <?= $player['team'] == 1 ? 'dire-color' : 'radiant-color' ?>"><?= $player['name'] ?></div></a>
                    </td>
                    <td class="level"><?= $player['level'] ?></td>
                    <td class="kills"><?= $player['kills'] ?></td>
                    <td class="death"><?= $player['death'] ?></td>
                    <td class="assists"><?= $player['assists'] ?></td>
                    <td class="gold gold-a"><?= $player['gold'] ?></td>
                    <td class="ld"><?= $player['last_hits'] ?>/<?= $player['denies'] ?></td>
                    <td class="gold gpm"><?= $player['gpm'] ?></td>
                    <td class="xpm"><?= $player['xpm'] ?></td>
                    <td class="net_worth"><?= $player['net_worth'] ?></td>
                    <td class="dota-player-items">
                        <?php foreach ($player['items'] as $item) { ?>
                            <div class="dota-player-item" style="background-image: url('http://www.trackdota.com/static/items/jpg_64/<?= $item['item_id'] ?>.jpg');">
                                <?php if($item['item_id'] != 0){ ?>
                                    <span class="min"><?= $item['time'] ?>m</span>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>

                </tbody>
            </table>
            </div>
        </div>

        <div class="dota-game-map col-md-4 col-sm-5 col-xs-12">
            <div class="dota-map">
                <div class="map">
                    <?php foreach ($game_data['players'] as $player) { 

                    	if($player['position_x'] <= 0){
                    		$pos_x = ($player['position_x'] + 7500) / 15000 * 100;
                    	}else{
                    		$pos_x = ($player['position_x'] + 7300) / 15000 * 100;
                    	}
                        
                        $pos_y = ($player['position_y'] + 7700) / 15000 * 100;

                    ?>

                    <div class="hero-on-map" style="left: <?= $pos_x ?>%; bottom: <?= $pos_y ?>%;" data-px="<?= $player['position_x'] ?>" data-py="<?= $player['position_y'] ?>">
                        <div class="hero-img" style="background-image: url('http://www.trackdota.com/static/heroes/png_o/32/<?= $player['hero_id'] ?>.png');"></div>
                    </div>
                    <?php } ?>
                    <img src="http://www.trackdota.com/static/map/map_700_800.jpg">
                </div>
            </div>
        </div>
        <div class="dota-chart col-md-8 col-sm-7 col-xs-12">
            <div class="dota-game-chart">
                <div class="selector-chart">
                    <div class="chart-item active" id="gpm_c" 
                    data-value='<?= json_encode(['radiant' => $game_data['radiant']['data']['gpm'], 'dire' => $game_data['dire']['data']['gpm']]) ?>'>GPM</div>
                    <div class="chart-item" id="gold_c" 
                    data-value='<?= json_encode(['radiant' => $game_data['radiant']['data']['gold'], 'dire' => $game_data['dire']['data']['gold']]) ?>'>Gold</div>
                    <div class="chart-item" id="xpm_c" 
                    data-value='<?= json_encode(['radiant' => $game_data['radiant']['data']['xpm'], 'dire' => $game_data['dire']['data']['xpm']]) ?>'>XPM</div>
                    <div class="chart-item" id="net_worth_c" 
                    data-value='<?= json_encode(['radiant' => $game_data['radiant']['data']['net_worth'], 'dire' => $game_data['dire']['data']['net_worth']]) ?>'>Net Worth</div>
                </div>
                <div id="dota-chart"></div>
            </div>
        </div>
    </div>
</div>
<?php }  ?>


    <div class="col-md-8 col-sm-12 col-xs-12">
    <?php if(!empty($streams)){ ?>
            <div class="stream-video">
                <div class="iframe">
                  <iframe
                        src="<?= $streams[0]['stream'] ?>"
                        frameborder="0"
                        scrolling="no"
                        allowfullscreen="true">
                </iframe> 

                </div>
        </div>
        <?php } ?>





<!-- <script type="text/javascript">
    var data = [{
            name: 'Wings Gaming EGB',
data: [1.34, 1.12, 1.44, 1.04, 1.74, 1.24, 1.34, 1.14, 1.20, 1.11]
        }, {
            name: 'Evil Geniuses EGB',
            data: [0.66, 0.88, 0.20, 0.12, 0.90, 1.00, 0.12, 0.92, 1.1, 0.7]
        },
        {
            name: 'Wings Gaming CSGL',
data: [1.34, 1.12, 1.44, 1.04, 1.74, 1.24, 1.34, 1.14, 1.20, 1.11]
        }, {
            name: 'Evil Geniuses CSGL',
            data: [0.66, 0.88, 0.20, 0.12, 0.90, 1.00, 0.12, 0.92, 1.1, 0.7]
        }];
</script>
<div id="chart" style="min-width: 310px; height: 300px; margin-top: 20px;"></div> -->
<?php //$this->registerJsFile('/js/m.js', ['depends' => ['yii\web\JqueryAsset', 'yii\jui\JuiAsset']]) ?>
<?php //$this->registerJsFile('https://code.highcharts.com/highcharts.js', ['depends' => ['yii\web\JqueryAsset', 'yii\jui\JuiAsset']]) ?>
<?php //$this->registerJsFile('https://code.highcharts.com/modules/exporting.js', ['depends' => ['yii\web\JqueryAsset', 'yii\jui\JuiAsset']]) ?>

<?php 
/*
$games_id = [];
if(isset($heroes)){
foreach ($heroes as $value) {
    if(isset($value->id) && !empty($value->id)){
        
        $data = LoadData::get('https://api.steampowered.com/IDOTA2Match_570/GetMatchDetails/v1/?key='.Yii::$app->params['steamKey'].'&format=json&match_id='.$value->id);
        $data = json_decode($data);
        array_push($games_id, $data);

    }
}
}


if($model['game'] == 0){
    if($model['over']){
        if(count($games_id) != 0){
            echo $this->render('_game.php', ['games_id' => $games_id, 'heroes_a' => $heroes_a]);
        }
    }else{ ?>
        <script type="text/javascript">
            var match = {
                team1: '<?= $model['team1_dotabuff'] ?>',
                team2: '<?= $model['team2_dotabuff'] ?>'
            }
        </script>
        <div style="margin-top: 15px; background-color: #121718;">
            <div class="game-details" id="live-game" style="display: none; ">
                <table class="game-details-table ">
                <?= $this->render('game/_head.php', ['class' => 'radiant']) ?>
                <?php for($g = 0; $g < 5; $g++){ 
                    echo $this->render('game/_body-row.php');
                }
                ?>
                <?= $this->render('game/_head.php', [ 'class' => 'dire']) ?>
                <?php for($g = 5; $g < 10; $g++){ 
                    echo $this->render('game/_body-row.php');
                }
                ?>
                </table>
            </div>
        </div>
    <?php }
}*/
?>

        <div class="timetable">
            <div class="wrap-tn-h3">
                <h3>Последние матчи <?= empty($model['team1_name']) ? $model['team1_id'] : $model['team1_name'] ?></h3>
                <?php League::matchInfo($last_team1, ['page', 'per-page']); ?>
            </div>
        </div>
        <div class="timetable">
            <div class="wrap-tn-h3">
                <h3>Последние матчи <?= empty($model['team2_name']) ? $model['team2_id'] : $model['team2_name'] ?></h3>
                <?php League::matchInfo($last_team2, ['page', 'per-page']); ?>
            </div>
        </div>

        <div id="comments" class="stream-comment">
            <div class="top-com">
                <?php if (!Yii::$app->user->isGuest) { ?>
                    <img src="<?= Yii::$app->user->identity->avatar ?>" alt="">
                    <?php $form = ActiveForm::begin(); ?>
                    <?= $form->field($modelComments, 'text')->textarea(['placeholder' => 'Оставить комментарий'])->label(false) ?>
                    <?= Html::submitButton('отправить') ?>
                    <?php ActiveForm::end(); ?>
                <?php } ?>
            </div>
            <?php $this->registerJsFile('/js/comment.js', ['depends' => ['yii\web\JqueryAsset', 'yii\jui\JuiAsset']]) ?>
            <h6><?= count($comments) ? "Комментариев (" . count($comments) . ")" : "Пока нет комментариев" ?></h6>

            <?php
            if (count($comments)) {
                //  var_dump("<pre>",$comments);
                $bestCom = $comments;
                ArrayHelper::multisort($bestCom, ['rait'], [SORT_DESC]);
                //            var_dump("<pre>",$comments);
                if ($bestCom[0]['rait'] > 0) {
                    $rait = $bestCom[0]['rait'];
                    ?>
                    <div class="main-comm">
                        <?= Yii::$app->user->identity->role == 'admin' ? "<a href='#' class='del-comment' data-del='" . $bestCom[0]['id_comment'] . "'><i class='fa fa-times'></i></a>" : "" ?>
                        <img src="<?= $bestCom[0]['avatar'] ?>" alt="">
                        <div class="descr-comm">
                            <h5><span class="best-com">лучший комментарий</span> <?= $bestCom[0]['username'] ?></h5>
                            <p><?= $bestCom[0]['text'] ?></p>
                        </div>

                        <div class="bottom-comm">
        <span><span class="icon"><i
                    class="fa fa-clock-o"></i></span><?= Yii::$app->formatter->asDatetime($bestCom[0]['created_at']) ?></span>
                            <!--        <span><a href="#">Ответить</a><span class="number"> (12)</span></span>-->
										<span class="wrap-btn">
											<button <?= Yii::$app->user->isGuest ? "disabled" : "" ?> class="minus"
                                                                                                      data-sign="plus"
                                                                                                      data-id_comment="<?= $bestCom[0]['id_comment'] ?>">
                                                <i class="fa fa-thumbs-up"></i></button>
											<button <?= Yii::$app->user->isGuest ? "disabled" : "" ?> class="plus"
                                                                                                      data-sign="minus"
                                                                                                      data-id_comment="<?= $bestCom[0]['id_comment'] ?>">
                                                <i
                                                    class="fa fa-thumbs-down"></i></button>
											<span> <?= $rait == 0 ? "" : ($rait > 0 ? "+" . $rait : $rait) ?></span>
										</span>
                        </div>
                    </div>
                    <?php
                }
            } ?>

            <?= \yii\widgets\ListView::widget([
                'dataProvider' => $dataProvider = new \yii\data\ArrayDataProvider([
                    'allModels' => $comments,
                    'pagination' => [
                        'pageSize' => 10,
                    ],
                ]),
                'itemOptions' => [
                    'tag' => 'div',
                    'class' => 'wrap-comment'
                ],
                'layout' => "{items}\n{summary}\n{pager}",
                'pager' => [
                    'options' => ['class' => 'pagin']
                ],
                'summaryOptions' => [
                    'tag' => 'p'
                ],
                'itemView' => '_list_comments',

            ]) ?>

        </div>
    </div>
</div>

<div class="col-md-4 col-sm-12 col-xs-12">
    <?php if(!empty($streams[0]['stream'])){ ?>
    <div class="stream-tabs">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" aria-expanded="true" id="chat_b"><i
                        class="fa fa-comments"></i></a></li>
            <li><a data-toggle="tab" aria-expanded="false" id="chats_b"><i
                        class="fa fa-video-camera"></i></a></li>

        </ul>
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active in" id="chat_a">
           
                <iframe frameborder="0"
                    scrolling="no"
                    id="chat_embed"
                    src="<?= $streams[0]['chat'] ?>">
                </iframe> 
            </div>
            <div class="tab-pane fade" id="first_a">
            <?php foreach ($streams as $value) { ?>
                <div class="striem-el clearfix">
                            <a class='league-stream' data-stream='<?= $value['stream'] ?>' data-chat='<?= $value['chat'] ?>'>
                                <img src='<?= $model['logo'] ?>'/>
                                <div class="striem-info">
                                    <span><?= $value['name'] ?></span>
<!--                                     <p><?= $model['date'] ?></p> -->
                                </div>
                            </a></div>
<?php } ?>

            </div>
        </div>
    </div>
    <?php } ?>
    <div class="game-wrap">
        <?= \app\widgets\banners\BannersWidget::widget() ?>

<?php League::activeLeague($model['game']); ?>
    </div>
</div>
