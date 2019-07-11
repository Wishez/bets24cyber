<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Banners */

$this->title = $model->id_banner;
$this->params['breadcrumbs'][] = ['label' => 'Банеры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banners-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id_banner], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id_banner], [
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
            'img:ntext',
            'link:ntext',
            'alt_text:ntext',
            'active',
        ],
    ]) ?>

</div>
