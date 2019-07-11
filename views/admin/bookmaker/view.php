<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Bookmaker */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Букмекеры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bookmaker-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id_bmaker], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id_bmaker], [
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
            'id_bmaker',
            'name',
            'description:ntext',
            'logo',
            'link',
            'visible',
        ],
    ]) ?>

</div>
