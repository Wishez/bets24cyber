<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\SocialNetwork */

$this->title = 'Создать ссылку на социальую сеть';
$this->params['breadcrumbs'][] = ['label' => 'Социальные сети', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="social-network-create">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
