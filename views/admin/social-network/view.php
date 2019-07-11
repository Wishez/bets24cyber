<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\SocialNetwork */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Социальные сети', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="social-network-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id_snetwork], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id_snetwork], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    </br>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'logo',
            'href',
            'enable',
        ],
    ]) ?>

</div>
