<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Bookmaker */

$this->title = 'Обновить букмекера: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Bookmakers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id_bmaker]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bookmaker-update">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
