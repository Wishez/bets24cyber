<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SocialNetwork */

$this->title = 'Обновить: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Социальные сети', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id_snetwork]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="social-network-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
