<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\forum\models\ForumCategory */

$this->title = 'Update Forum Category: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Forum Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id_fcategory]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="forum-category-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
