<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Bookmaker */

$this->title = 'Создать букмекерскую контору';
$this->params['breadcrumbs'][] = ['label' => 'Букмекеры ', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bookmaker-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
