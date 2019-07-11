<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\StaticPageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Статические страницы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="static-page-index">
    <p>
        <?= Html::a('Создать новую статическую страницу', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'title',
            'short_description',
            'description:ntext',
            'href'=>
            [
                'label' => 'Ссылка на ресурс',
                'value'=>function($model)
                {
                    return Url::to(['@web/site/static-page/','id'=>$model['id_statp']],true);
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
