<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TopStreamsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Стримы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="top-streams-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать стрим', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id_tsream',
            'title',
            'date',
            //'views',
            //'likes',
             'link',
            // 'img',
            // 'visible',

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' =>
                 [
                     'gls' => function ($url, $model_judge, $key)
                     {
                         if ($model_judge['visible']==0)
                             return Html::a('<span class="glyphicon glyphicon-unchecked"></span>', \yii\helpers\Url::to(['@web/admin/streams/show-news/', 'id' => $model_judge['id_tsream'],   'key'=>1 ]), ['title' => Yii::t('yii', 'Добавить стрим в топ')]);
                         else
                             return Html::a('<span class="glyphicon glyphicon-check"></span>', \yii\helpers\Url::to(['@web/admin/streams/show-news/', 'id' => $model_judge['id_tsream'],    'key'=>0]), ['title' => Yii::t('yii', 'Убрать стрим из топа')]);
                     },

                 ],
                'template' => '{view}&nbsp;&nbsp;{update}&nbsp;&nbsp;{delete}&nbsp;&nbsp;{gls}',


            ],
        ],
    ]); ?>

</div>
