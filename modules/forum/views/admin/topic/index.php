<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\forum\models\ForumTopicSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Forum Topics';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="forum-topic-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Forum Topic', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_ftopic',
            'name',
            'id_fcategory',
            'id_user_owner',
            'created_at',
            // 'updated_at',
            // 'views',
            // 'enable',
            // 'visible',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
