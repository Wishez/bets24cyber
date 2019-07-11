<?php
use app\models\Team;
use app\models\Match;
use app\models\League;
use yii\bootstrap\ActiveForm;

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Турниры';
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
    h4{
        margin-top: 20px;
    }
</style>

<div class="league-index">


<?= $this->registerJsFile('https://code.jquery.com/ui/1.12.0/jquery-ui.min.js', ['depends' => 'yii\web\JqueryAsset']); ?>

<?= $this->registerJsFile('/js/inx.js', ['depends' => 'yii\web\JqueryAsset']); ?>
<?=$this->registerCssFile('/css/main.css');?>
<h4>Текущие турниры</h4>
<?= $this->render('template/_league', ['model' => $model_active, 'search'=>$search]) ?>

<h4>Прошедшие турниры</h4>
<?= $this->render('template/_league', ['model' => $model_last, 'search'=>$search]) ?>

<?php
    //     $dataProvider = new \yii\data\ActiveDataProvider(
    //         [
    //             'query' => $model,
    //             'pagination' => [
    //                 'pageSize' => 30,
    //             ],
    //         ]
    //     );

    // echo GridView::widget([
    //     'dataProvider' => $dataProvider,
    //     'columns' => [
    //         ['class' => 'yii\grid\SerialColumn'],
    //         'name',
    //         'date_start',
    //         'game' => [
    //         'label' => 'game',
    //         'value' => function($model){
    //             return $model['game'] == 0 ? 'dota2' : 'csgo';
    //         }
    //         ],
    //         'prizepool',

    //         [
    //             'class' => \yii\grid\ActionColumn::className(),
    //             'buttons' => [
    //                 'delete',
    //                 'update',
    //                 'update-league' => function($model){
    //                     return '<a href="'.$model.'" title="Обновить" aria-label="Обновить" data-pjax="0"><span class="glyphicon glyphicon-repeat"></span></a>';
    //                 }
    //             ],
    //             'template' => '&nbsp;&nbsp;{delete}&nbsp;&nbsp;{update}&nbsp;&nbsp;{update-league}',
    //         ]


    //     ],
    // ]);
?>


</div>