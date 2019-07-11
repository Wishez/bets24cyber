<?php
/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 17.06.2016
 * Time: 16:03
 */

use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
$this->title = "Редактирование реферальных ссылок";
$this->params['breadcrumbs'][] = ['label' => 'Партнеры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
//var_dump($referal_url);
$DataProvider = new ActiveDataProvider([
    'query' => $referal_url,
    'pagination' => ['pageSize' => 10,]
]);
?>
<p>
    <span>Кошелек: </span><?= $wallet?>
</p>
<p>
    <?= Html::a('Create реферальную ссылку', ['create', 'id' => $user_id], ['class' => 'btn btn-success']) ?>
</p>
<?= GridView::widget([
    'dataProvider' => $DataProvider,
//    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
//        'user_id',
        'ref_url',

        [
            'class' => 'yii\grid\ActionColumn',
            'buttons' =>[
                'edit' => function ($url, $model, $key)
                { return Html::a('<span class="glyphicon glyphicon-pencil"></span>', \yii\helpers\Url::to(['@web/admin/referals/edit-ref-url', 'id' => $model['id_ref'] ]), ['title' => Yii::t('yii', 'Редактировать')]);},
//
                'view' => function ($url, $model, $key)
                { return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', \yii\helpers\Url::to(['@web/admin/referals/view-details', 'id' => $model['id_ref']]), ['title' => Yii::t('yii', 'Просмотреть')]);},
//
//
//                'delete' => function ($url, $model_judge, $key)
//                {
//                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', \yii\helpers\Url::to(['@web/league-dota2/delta/', 'id' => $model_judge['id_match'], 'idl' => $model_judge['id_league'], ]), ['title' => Yii::t('yii', 'Удалить')]);
//                },
//
//
//                'gls' => function ($url, $model_judge, $key)
//                {
//                    if ($model_judge['match_sort_number']==0)
//                        return Html::a('<span class="glyphicon glyphicon-unchecked"></span>', \yii\helpers\Url::to(['@web/league-dota2/show-game/', 'id' => $model_judge['id_match'], 'idl' => $model_judge['id_league'], 'key'=>1 ]), ['title' => Yii::t('yii', 'Добавить матч в список')]);
//                    else
//                        return Html::a('<span class="glyphicon glyphicon-check"></span>', \yii\helpers\Url::to(['@web/league-dota2/show-game/', 'id' => $model_judge['id_match'], 'idl' => $model_judge['id_league'],  'key'=>0]), ['title' => Yii::t('yii', 'Удалить матч из списка')]);
//                },
//
//                'book'=> function ($url, $model_judge, $key)
//                {
//                    if ($model_judge['flag_best_bookmaker']==0)
//                        return Html::a('<span class="glyphicon glyphicon-star-empty"></span>', \yii\helpers\Url::to(['@web/league-dota2/show-book-offer/', 'id' => $model_judge['id_match'], 'idl' => $model_judge['id_league'], 'key'=>1 ]), ['title' => Yii::t('yii', 'Добавить матч в список')]);
//                    else
//                        return Html::a('<span class="glyphicon glyphicon-star"></span>', \yii\helpers\Url::to(['@web/league-dota2/show-book-offer/', 'id' => $model_judge['id_match'], 'idl' => $model_judge['id_league'],  'key'=>0]), ['title' => Yii::t('yii', 'Удалить матч из списка')]);
//                },
//
            ],
            'template' => '{view}&nbsp;&nbsp;{edit}&nbsp;&nbsp;{delete}',
        ],
    ],
]); ?>


