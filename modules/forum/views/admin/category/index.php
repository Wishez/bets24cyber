<?php

use yii\helpers\Html;
use yii\grid\GridView;

$main_cat = \app\modules\forum\models\ForumCategory::getCategorys();
//var_dump($main_cat);

/* @var $this yii\web\View */
/* @var $searchModel app\modules\forum\models\ForumCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Forum Categories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="forum-category-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Forum Category', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id_fcategory',
            'name',
            'id_owner_category',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
