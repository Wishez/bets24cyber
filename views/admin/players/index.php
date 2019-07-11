<?php
use app\models\Team;
use app\models\Match;
use app\models\League;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;
use yii\helpers\Html;
use yii\helpers\Url;
$this->title = 'Игроки';
$this->params['breadcrumbs'][] = $this->title;

?>

<?= $this->registerJsFile('/js/update-teams.js', ['depends' => 'yii\web\JqueryAsset']); ?>

<div class="league-index">
    <div class="row" style="margin-top: 20px;">
        <form method="GET">
            <div class="col-md-11"><input class="form-control" placeholder="Поиск" name="query"></div>
            <div class="col-md-1"><button class="btn btn-primary">Искать</button></div>
        </form>
        
    </div>

<table class="league-index-table">
        <tr>
            <th width="31"></th>
            <th width="40">Лого</th>
            <th>Ник</th>
            <th>Имя</th>
            <th>Игра</th>
            <th>Команда</th>
            <th>Заработано</th>
            <th>Действие</th>
        </tr>
    <?= ListView::widget([
    'dataProvider' => new \yii\data\ActiveDataProvider(
            [
                'query' => $model,
                'pagination' => [
                    'pageSize' => 50,
                ],
            ]
        ),
                'layout' => "{items}\n{pager}",
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model) {
            $game = $model['game'] == 0 ? 'dota2' : 'csgo';
            $html = '<tr>
                <td><i class="glyphicon glyphicon-th-large"></i></td>
                <td><img style="width: 30px; height: 30px;" src="'.$model->image.'"></td>
                <td>'.$model->player_id.'</td>
                <td>'.$model->name.'</td>
                <td>'.($model->game == 0 ? 'dota2' : 'csgo').'</td>
                <td>'.$model->team->name.'</td>
                <td>'.$model->earning.'$</td>
                <td><a class="btn btn-primary glyphicon glyphicon-pencil" href="/admin/players/edit?id='.$model->id.'"></a>
                    <span class="btn btn-primary glyphicon glyphicon-repeat update-player" update="'.$model->id.'"></span>
                    </td>
            </tr>';
            return $html;

        },
    ]) ?>


</table>



</div>