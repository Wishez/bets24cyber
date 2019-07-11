<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\StaticPage */

$this->title = 'Создание статической страницы';
$this->params['breadcrumbs'][] = ['label' => 'Static Pages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="static-page-create">



    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
