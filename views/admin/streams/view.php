<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\TopStreams */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Top Streams', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="top-streams-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id_tsream], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id_tsream], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

            'title',
            'date',
            'views',
            'likes',
            'link',
            'img',

        ],
    ]) ?>

</div>
