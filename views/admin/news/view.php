<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\News */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id_news], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id_news], [
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
            'title:ntext',
            'desc:ntext',
            'text:ntext',
            'img' => [
                'attribute' => 'img',
                'format' => 'html',
                'value' => $model->img ? '<img src="' . $model->img . '" alt="' . $model->title . '" width="200">' : ""
            ],
            'id_user',
            'id_category',
            'id_bmaker',
            'show_in_footer' => [
                'attribute' => 'show_in_footer',
                "label" => 'Место',
                'value' => $model->show_in_footer == 0 ? 'left-block' : 'up-block'
            ],
            'created_at:datetime',
            'updated_at:datetime',
            'sort',
        ],
    ]) ?>

</div>
