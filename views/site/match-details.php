<?php

use app\models\Bookmaker;
use app\models\Favorites;
use app\models\League;
use app\models\DotaGame;
use app\models\Match;
use app\models\LoadData;
use app\models\Team;
use app\models\DotaHelper;
use app\models\CsgoHltv;
use yii\db\Query;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $model app\models\League */
/* @var $form ActiveForm */
$this->params['class_page'] = 'stream-body';
$this->params['breadcrumbs'][] = ['label' => 'Матчи', 'url' => ['/match-info']];

if($match->league->event == 1){
    $this->params['breadcrumbs'][] = ['label' => $match->league->main->name, 'url' => Url::to(['site/league-details', 'id' => $match->league->main->id])];
}
$this->params['breadcrumbs'][] = ['label' => $match->league->name, 'url' => Url::to(['site/league-details', 'id' => $match->league->id])];

$this->params['breadcrumbs'][] =  $match->teamFirst->name.' vs '.$match->teamSecond->name;

$this->title = 'Матч '.$match->teamFirst->name.' против '.$match->teamSecond->name.' - '.$match->date;
$this->description = 'Детали схватки '.$match->teamFirst->name.' против '.$match->teamSecond->name.' в киберспортивном матче '.$match->league->name.'.';

$this->registerJsFile('/js/addFavorites.js', ['depends' => ['yii\web\JqueryAsset', 'yii\jui\JuiAsset']]);
$this->registerJsFile('/js/match.js', ['depends' => ['yii\web\JqueryAsset', 'yii\jui\JuiAsset']]);

$maps = $match->series;
if($match->league->game == DotaHelper::CSGO_CODE){
    if(!empty($match->csgoGames)){
        $maps = $match->csgoGames[0]['series'];
    }
}
?>

<script type="text/javascript">
    var match_id = '<?= $match->id ?>';
</script>

<div class="hr hr-match"></div>
<div class="site-match-details">
    <div class="top-descr">
        <a href="<?= Url::to(['site/league-details', 'id' => $match->league->id]) ?>"><h2><?= $match->league->name ?></h2></a>
        <h3><?= date('d.m.Y H:i', strtotime($match->date)) ?> <?= $maps > 0 ? ' Best of '.$maps : '' ?></h3>
    </div>

    <div class="counter clearfix">
        <div class="col-md-4 col-sm-4 col-sm-push-4 col-xs-12 fl-clock">

            <div class="wrap-clock">
            <?php if($match->gameOver == 1){ ?>

                <div class="over">Матч завершился</div>
                <div class="score"><?= $match->score ?></div>

            <?php }else if(strtotime($match->date) < time()){ ?>
                <div class="over">Live</div>
                <div class="score"><?= $match->score ?></div>
            <?php }else{  ?>
                <div class="over">Матч начнется</div>
                <div class="clock-back" data-time="<?= strtotime($match->date) - time() ?>"></div>
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

        <?php if($match->league->game == DotaHelper::CSGO_CODE){ 
                $csgo_game = (new Query())->select('*')->from('hltv_csgo')->where(['match_id' => $match->id])->one();
                $csgo_data = null;
                if(!empty($csgo_game)){
                    $last_games = (new Query())->select('game_id as id')->from('hltv_matches')->where(['match_id' => $csgo_game['hltv_id']])->all();
                // $dota_games = (new Query())->select('match_id, active')
                //     ->from('dota_games')->where(['team1' => $model['team1_id'], 'team2' => $model['team2_id'], 'date' => $model['date']])->orderBy('active')->all();
                // if(!empty($dota_games)){
                //     $last = $dota_games[count($dota_games) - 1]['active'] == 1 ? true : false;
                //     $match_id = $dota_games[count($dota_games) - 1]['active'] == 1 ? $dota_games[count($dota_games) - 1]['match_id'] : $dota_games[0]['match_id'];
                //     $game_data = DotaGame::GetGame($match_id);
                    if($csgo_game['active'] == 1){
                        $select = count($last_games);
                    }else{
                        $select = 0;
                        if(!empty($last_games)){
                            $csgo_data = CsgoHltv::loadMatch($last_games[0]['id']);
                        }
                        

                    }
        ?>
            <div class="dota-game-select" active="<?= $csgo_game['active'] ?>" id="<?= $csgo_game['hltv_id'] ?>">
                <?php foreach ($last_games as $key => $game) { ?>
                    <div class="selecter-dota <?= $key == $select ? 'active' : '' ?>" match-id="<?= $game['id'] ?>">Игра <?= $key + 1 ?></div>
                <?php } ?>
                <?php if($csgo_game['active'] == 1){ ?>
                    <div class="selecter-dota active" match-id="0">Игра <?= count($last_games) + 1 ?></div>
                <?php } ?>
            </div>
        <?php } }else if($match->league->game == DotaHelper::DOTA2_CODE){ 
                $dota_games = (new Query())->select('match_id, active')
                    ->from('dota_games')->where(['m_id' => $match->id])->orderBy('active')->all();
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
                        $active = false;

                        if($dota_games[$i]['active'] == 1){
                            $active = true;
                        }
                    ?>
                    <div class="selecter-dota <?= $selected ? 'active' : '' ?>" match-id="<?= $dota_games[$i]['match_id'] ?>" active="<?= $dota_games[$i]['active'] ?>" pat-active="0">Игра <?= $i + 1 ?></div>
                    <?php if(!$active && $i == count($dota_games) - 1 && $match->gameOver == 0){ ?>
                        <div class="selecter-dota active" active="0" pat-active="1" match-id="-1">Игра <?= $i + 2 ?></div>    

                    <?php } ?>

                <?php } ?>
                </div>
                <?php }else if($match->gameOver == 0){ ?> 
                    <div class="dota-game-select">
                        <div class="selecter-dota active" active="0" pat-active="1" match-id="-1">Игра 1</div>    
                    </div>
                <?php }

                } ?>
                
        </div>

        <div class="col-md-4 col-sm-4 col-sm-pull-4 col-xs-12">

            <div class="col-md-6 col-sm-12 col-xs-7" style="float: right;">
                <div class="wram-list-img">
                	<a href="<?= Url::to(['site/team-info', 'id' => $match->teamFirst->id]) ?>">
	                    <div class="team-blocks">
	                        <div class="team-logo" style="background-image: url('<?= $match->teamFirst->logo ?>')"></div>
	                        <img class="team1-img-match" src="<?= $match->teamFirst->flagUrl ?>">
	                        <div class="team-name"><?= $match->teamFirst->name ?></div>
	                    </div>
                    </a>
                </div>
            </div>
                        <div class="col-md-6 col-sm-12 col-xs-5 players-of-team-c" style="float: left;">
                <div class="players-of-team">
                <?php foreach ($match->teamFirst->players as $player) { ?>
                    <div class="player-r">
                        <div class="nick" style="text-align: right; margin-right: 6px;">
                            <a href="<?= Url::to(['site/player-details', 'id' => $player->id]); ?>"><?= $player->player_id ?></a>
                        </div>
                        <div class="flag" style="background-image: url('<?= $player->flagUrl ?>');"></div>
                        
                    </div>
                <?php } ?>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-sm-4 col-sm-4 col-xs-12">
            <div class="col-md-6 col-sm-12 col-xs-7">
                <div class="wram-list-img">
                	<a href="<?= Url::to(['site/team-info', 'id' => $match->teamSecond->id]) ?>">
	                    <div class="team-blocks">
	                        <div class="team-logo" style="background-image: url('<?= $match->teamSecond->logo ?>')"></div>
	                        <img class="team2-img-match" src="<?= $match->teamSecond->flagUrl ?>">
	                        <div class="team-name"><?= $match->teamSecond->name ?></div>
	                    </div>
                    </a>
                </div>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-5 players-of-team-c">
                <div class="players-of-team">
                <?php foreach ($match->teamSecond->players as $player) { ?>
                    <div class="player-r">
                        <div class="flag" style="background-image: url('<?= $player->flagUrl ?>');"></div>
                        <div class="nick">
                            <a href="<?= Url::to(['site/player-details', 'id' => $player->id]); ?>"><?= $player->player_id ?></a>
                        </div>      
                    </div>
                <?php } ?>
                </div>
            </div>
        </div>

<?php

/*
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
         <?php  }}*/ ?>
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
$this->registerJsFile('//code.highcharts.com/highcharts.js');

if($match->gameOver == 0){
    $twitch = ['data' => $match->streams, 'type' => 0];
}else{
    $twitch = ['data' => $match->vods, 'type' => 1];
}

if($match->league->game == 0){
    if((!empty($game_data) && $match->gameOver == 1) || ($match->gameOver == 0)&&!empty($game_data) ){
        echo $this->render('game/dota2.php', ['game_data' => isset($game_data) ? $game_data : [], 'live' => (strtotime($match->date) <= time())]);
        $this->registerJsFile('/js/dota.js', ['depends' => ['yii\web\JqueryAsset', 'yii\jui\JuiAsset']]);
    }
}else{
    $teams_logos = [$match->teamFirst->spec_id => $match->teamFirst->logo, $match->teamSecond->spec_id => $match->teamSecond->logo];
    
    if((!empty($csgo_data) && $match->gameOver == 1) || $match->gameOver == 0){


        echo $this->render('game/csgo.php', ['model' => $csgo_data, 'teams' => $teams_logos, 'live' => (strtotime($match->date) <= time())]);

        $this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.3/socket.io.min.js');
        if($match->gameOver == 0 || $csgo_game['active'] == 0){
            $this->registerJsFile('/js/csgo.js', ['depends' => ['yii\web\JqueryAsset', 'yii\jui\JuiAsset']]);
        }
    }
    
}

?>


    <div class="col-md-8 col-sm-12 col-xs-12">
    <?php if(!empty($twitch['data'])){ ?>
            <div class="stream-video">
                <div class="iframe">
                  <iframe
                        id="stream_embed"
                        autoplay="0"
                        src="<?= $twitch['type'] == 0 ? 'https://player.twitch.tv/?channel='.$twitch['data'][0]['channel'] : $twitch['data'][0]['video'] ?>"
                        frameborder="0"
                        scrolling="no"
                        allowfullscreen="true">
                </iframe>
                </div>
        </div>
        <?php }else if(strtotime($match->date) <= time() && $match->gameOver == 0){ ?>
            <div class="wait-stream">
                <div class="animation-holder">
                    <img src="/img/5.svg" class="animation">
                </div>
                <div class="stream-text">Мы осуществляем поиск стримов</div>
            </div>
        <?php } ?>


1111
<?php
    $this->registerJs(
        '$("document").ready(function(){
            $("body").on("beforeSubmit", "#order_form, .repay_form", function () {
             var form = $(this);
             // return false if form still have some validation errors
             if (form.find(".has-error").length) {
                  return false;
             }
             // submit form
             $.ajax({
                  url: form.attr("action"),
                  type: "post",
                  data: form.serialize(),
                  success: function (response) {
                       $("#pModal").modal("show").find(".modal-content").html(response);
                  }
             });
             return false;
        });
        });'
    );
?>
<?php
    $this->registerJs(
        '$("document").ready(function(){
            $("body").on("click", "#order_pay", function () {
                 var id = $(this).data("id");
                 $.ajax({
                      url: "/pay-order",
                      type: "post",
                      data: {order_id:id},
                      success: function (response) {
                           $("#pModal").modal("show").find(".modal-content").html(response);
                           $.pjax.reload({container:"#orders_list"}); //refresh the grid
                      }
                 });
            });
            $("body").on("click", "#order_plain", function () {
                 var id = $(this).data("id");
                 $.ajax({
                      url: "/plain-order",
                      type: "post",
                      data: {order_id:id},
                      success: function (response) {
                          $("#pModal").modal("show").find(".modal-content").html(response);
                           $.pjax.reload({container:"#orders_list"}); //refresh the grid
                      }
                 });
            });
        });'
    );
?> 
<?php if ((strtotime($match->date)+($match->orders_time*60)) >= time()&&$match->orders_active): ?>
    

<?php $form = ActiveForm::begin(['action'=>['site/add-order'],'id' => 'order_form']); ?>
<div class="col-md-12">
    <?= $form->field($orderModel, 'author_id')->hiddenInput(['value' => Yii::$app->user->id])->label(false) ?>
    <?= $form->field($orderModel, 'match_id')->hiddenInput(['value' => $match->id])->label(false) ?>
    <div class="col-md-3">
        <?= $form->field($orderModel, 'summ')->textInput() ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($orderModel, 'rate')->textInput() ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($orderModel, 'winner')->dropDownList([
            1=>$match->teamFirst->name ,
            2=>$match->teamSecond->name ,
            
        ])?>
    </div>
    <div class="col-md-3">
    <?= Html::submitButton('Создать ордер') ?>
    </div>

</div>
<?php ActiveForm::end(); ?>
<?php Pjax::begin(['id' => 'orders_list', 'enablePushState' => false]) ?>
<div class="orders">
    <div class="row">
        <div class="col-md-2">Комманда</div>
        <div class="col-md-2">Сумма ставки</div>
        <div class="col-md-2">Коефициент</div>
        <div class="col-md-2">Уже поставили</div>
        <div class="col-md-2">Ваша ставка</div>
        <div class="col-md-2">Действия</div>

    </div>
    <?php foreach ($orders as $key => $order): ?>
    <?
    // var_dump($orders);
    // exit();
    $team = $order->team;?>
    <div class="row" >
        <?php $form = ActiveForm::begin([
        'action'=>['site/repay-order'],
        'id' => 'repay_form_'.$order->id,
        'options'=>['class'=>'repay_form'],
        'enableAjaxValidation' => true,
        'validationUrl' => ['site/ajax-validate', 'mod'=>'PayOrder']
        ]); ?>
        <div class="col-md-2"><?=$match->$team->name?></div>
        <div class="col-md-2"><?=$order->summ?></div>
        <div class="col-md-2"><?=$order->rate?></div>
        <div class="col-md-2"><?=(int)$order->payersTransactionsSum?></div>
        <div class="col-md-2">
            <?= $form->field($payorderModel, 'summ')->textInput(['type'=>'number'])->label(false) ?>
            <?= $form->field($payorderModel, 'order_id')->hiddenInput(['value'=>$order->id])->label(false) ?>
            <?= $form->field($payorderModel, 'team_id')->hiddenInput(['value'=>($order->winner==1 ? 2 : 1)])->label(false) ?>
        </div>
        <div class="col-md-2">
            <button form="repay_form_<?=$order->id?>" class="repay-order">Оплатить</button>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <?php endforeach ?>
</div>
<?php Pjax::end(); ?>
<?php endif ?>    






<?
yii\bootstrap\Modal::begin([
'id'=>'pModal',
]);

yii\bootstrap\Modal::end();
?>


<script type="text/javascript">
    var match_over = '<?= $match->gameOver ?>';
    var team1_name = '<?= $match->teamFirst->name ?>';
    var team2_name = '<?= $match->teamSecond->name ?>';

</script>
<?php 

$bk = $match->getBets()->select('b.*, d.*')->alias('b')->leftJoin('bk_desc d', 'd.type = b.type')->asArray()->all();
$bets = Bookmaker::getBets($bk);
$time = 0;

foreach ($bets as $key => $bet) {
    $time = 10 * 60 - (time() - $bet['k1'][count($bet['k1']) - 1][0]);
    break;
}
if($time <= 0){
    $time = 10;
}
?>
<?php if(!empty($bk) && file_get_contents(Yii::getAlias('@app').'/bk.work') == 1){ ?>
        <div class="timetable">
            <div class="bk-header">
                <span class="bk-tname"><?= $match->teamFirst->name ?></span>
                <span class="bk-title">Коэффициенты БК</span>
                <span class="bk-tname"><?= $match->teamSecond->name ?></span>
            </div>
            <div class="bk-odds">
                <?php foreach ($bk as $odd) { $name = $odd['name']?>
	                <?php if($bets[$name]['k1'][count($bets[$name]['k1']) - 1][1] > 0 && $bets[$name]['k2'][count($bets[$name]['k2']) - 1][1] > 0){ ?>
	                <div class="bk-odd" id="koff-<?= $name ?>">
	                    <div class="odd-num"><?= round($bets[$name]['k1'][count($bets[$name]['k1']) - 1][1], 2) ?></div>
	                    <div class="bk-type">
	                        <a href="<?= !empty($odd['link']) ? $odd['link'] : '#' ?>"><div class="bk-img" style="background-image: url(<?= $odd['img'] ?>);"></div></a>
	                    </div>
	                    <div class="odd-num"><?= round($bets[$name]['k2'][count($bets[$name]['k2']) - 1][1], 2) ?></div>
	                </div>
                <?php } } ?>
            </div>
            <div class="dota-chart col-md-12 col-sm-12 col-xs-12">
                <div class="dota-game-chart">
                    <div class="selector-chart">
                        <?php $s = true; foreach($bets as $key => $b){ ?>
    	                	<?php if($b['k1'][count($b['k1']) - 1][1] > 0 && $b['k2'][count($b['k2']) - 1][1] > 0){ ?>
                            	<div class="bk-button <?= $s ? 'active-x' : '' ?> bk-graph-<?= $key ?>" d='<?= json_encode($b)?>'><?= $key ?></div>
                        <?php $s = false; } } ?>
                    </div>
                    <div id="bk-chart"></div>
                </div>
            </div>
            <?php if(strtotime($match->date) > time()){ ?>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="bk-timer" id="bk-timer" time="<?= $time ?>">Обновление через ....</div>
                </div>
            <?php } ?>
        </div>
<?php } ?>
        <div class="timetable">
            <div class="wrap-tn-h3">
                <h3>История встреч <?= $match->teamFirst->name ?> - <?= $match->teamSecond->name ?></h3>
                <?= $this->render('templates/_matches_grid_s', ['model' => $match->teamFirst->getHeadToHead($match->teamSecond->team_id)]); ?>
            </div>
        </div>
        <div class="timetable">
            <div class="wrap-tn-h3">
                <h3>Последние матчи <?= $match->teamFirst->name ?></h3>
                <?= $this->render('templates/_matches_grid_s', ['model' => $match->teamFirst->getLastMatches()]); ?>
            </div>
        </div>
        <div class="timetable">
            <div class="wrap-tn-h3">
                <h3>Последние матчи <?= $match->teamSecond->name ?></h3>
                <?= $this->render('templates/_matches_grid_s', ['model' => $match->teamSecond->getLastMatches()]); ?>
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
            <h6><?= count($comments) ? "Комментариев (".count($comments).")" : "Пока нет комментариев" ?></h6>

            <?php
            if (!empty($comments)) {
                $bestCom = $comments;
                ArrayHelper::multisort($bestCom, ['rait'], [SORT_DESC]);
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
                        <span>
                            <span class="icon">
                                <i class="fa fa-clock-o"></i>
                            </span>
                            <?= Yii::$app->formatter->asDatetime($bestCom[0]['created_at']) ?>
                        </span>
						<span class="wrap-btn">
							<button <?= Yii::$app->user->isGuest ? "disabled" : "" ?> class="minus" data-sign="plus" data-id_comment="<?= $bestCom[0]['id_comment'] ?>">
                                <i class="fa fa-thumbs-up"></i>
                            </button>
						  
                            <button <?= Yii::$app->user->isGuest ? "disabled" : "" ?> class="plus" data-sign="minus" data-id_comment="<?= $bestCom[0]['id_comment'] ?>">
                                <i class="fa fa-thumbs-down"></i>
                            </button>
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
    <?php if(!empty($twitch['data']) || (strtotime($match->date) <= time() && $match->gameOver == 0)){ ?>
    <div class="stream-tabs">
        <ul class="nav nav-tabs">
            <li class="active">
                <a data-toggle="tab" aria-expanded="true" id="chat">
                    <i class="fa fa-comments"></i>
                </a>
            </li>
            
            <li>
                <a data-toggle="tab" aria-expanded="false" id="chats">
                    <i class="fa fa-video-camera"></i>
                </a>
            </li>

        </ul>
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active in" id="chat_a">
                <?php if(!empty($twitch['data']) && $twitch['type'] == 0){ ?>
                <iframe frameborder="0"
                    scrolling="no"
                    id="chat_embed"
                    src="https://www.twitch.tv/<?= $twitch['data'][0]['channel'] ?>/chat">
                </iframe> 
                <?php } ?>
            </div>

            <div class="tab-pane fade" id="first_a">
                <div class="scroll-stream">
                <?php if(!empty($twitch['data']) && $twitch['type'] == 0){ ?>
                    <?php foreach ($twitch['data'] as $stream) { ?>
                        <div class="striem-el clearfix new-stream" data-type="0" data-stream="<?= $stream['channel'] ?>" >
                            <img src='/img/flags/<?= $stream['country'] ?>.png' onerror="this.src='/img/flags/eflag.png'">
                            <div class="striem-info">
                                <span><?= $stream['channel'] ?> (<?= strtoupper($stream['country']) ?>)</span>
                            </div>
                        </div> 
                        <?php } ?>
                    <?php }else if(!empty($twitch['data'])){ ?>
                        <?php foreach ($twitch['data'] as $key => $stream) { ?>
                            <div class="striem-el clearfix new-stream" data-type="1" data-video="<?= $stream['video'] ?>" >
                                <div class="striem-info">
                                    <span>Запись игры #<?= $key + 1?></span>
                                </div>
                            </div> 
                        <?php } ?>
                    <?php } ?>
            
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="game-wrap">
        <?= \app\widgets\banners\BannersWidget::widget() ?>
        <?= $this->render('templates/_active_leagues', ['game' => $match->league->game]); ?>
    </div>
</div>
