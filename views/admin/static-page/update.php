<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\StaticPage */

$this->title = 'Обновить: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Static Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id_statp]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="static-page-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
