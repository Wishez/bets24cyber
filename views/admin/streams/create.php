<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TopStreams */

$this->title = 'Создать стрим';
$this->params['breadcrumbs'][] = ['label' => 'Top Streams', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="top-streams-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
