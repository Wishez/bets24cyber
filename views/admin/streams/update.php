<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TopStreams */

$this->title = 'Update Top Streams: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Top Streams', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id_tsream]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="top-streams-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
