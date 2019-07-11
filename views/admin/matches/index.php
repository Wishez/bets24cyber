<?php
use app\models\Team;
use app\models\Match;
use app\models\League;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;
use yii\helpers\Html;
use yii\helpers\Url;
$this->title = 'Матчи';
$this->params['breadcrumbs'][] = $this->title;
?>

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
            <th>Команда 1</th>
            <th>Счет</th>
            <th>Команда 2</th>
            <th>Игра</th>
            <th>Дата</th>
            <th>Действие</th>
        </tr>
    <?= ListView::widget([
    'dataProvider' => new \yii\data\ActiveDataProvider(
            [
                'query' => $model,
                'pagination' => [
                    'pageSize' => 15,
                ],
            ]
        ),
                'layout' => "{items}\n{pager}",
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model) {
            $html = '<tr>
                <td><i class="glyphicon glyphicon-th-large"></i></td>
                <td><img style="width: 30px; height: 30px; margin-right: 10px;" src="'.$model->teamOne->logo.'">'.$model->teamOne->name.'</td>
                <td>'.$model->score.'</td>
                <td><img style="width: 30px; height: 30px; margin-right: 10px;" src="'.$model->teamTwo->logo.'">'.$model->teamTwo->name.'</td>
                <td>'.($model->league->game == 0 ? 'dota2' : 'csgo').'</td>
                <td>'.$model->date.'</td>
                <td><a class="glyphicon glyphicon-pencil" href="/admin/matches/edit?id='.$model->id.'"></a></td>
            </tr>';
            return $html;

        },
    ]) ?>


</table>



</div>