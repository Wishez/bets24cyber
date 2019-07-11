<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\forum\models\ForumPost */

$this->title = 'Update Forum Post: ' . ' ' . $model->id_fpost;
$this->params['breadcrumbs'][] = ['label' => 'Forum Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id_fpost, 'url' => ['view', 'id' => $model->id_fpost]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="forum-post-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
