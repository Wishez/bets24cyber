<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\forum\models\ForumPost */

$this->title = $model->id_fpost;
$this->params['breadcrumbs'][] = ['label' => 'Forum Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="forum-post-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id_fpost], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id_fpost], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id_fpost',
            'id_ftopic',
            'id_user',
            'created_at',
            'updated_at',
            'description:ntext',
        ],
    ]) ?>

</div>
