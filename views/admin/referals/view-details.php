<?php
/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 17.06.2016
 * Time: 17:30
 */
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;

/* @var $ref_details app\models\ReferalsDetail */

$this->title = "Редактирование данных";
$this->params['breadcrumbs'][] = ['label' => 'Партнеры', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'реферальные ссылки', 'url' => ['view', 'id' => \app\models\ReferalsUrl::findOne(['id_ref' => $id_ref])->user_id]];
$this->params['breadcrumbs'][] = ''
//var_dump($ref_details);
?>
<p>
    <?= Html::a('Создать', ['@web/admin/referals/create-ref-det', 'id' => $id_ref], ['class' => 'btn btn-success']) ?>
</p>
<?php
//var_dump($ref_details);
$DataProvider = new ActiveDataProvider([
    'query' => $ref_details,
    'pagination' => ['pageSize' => 10,]
]);
?>

<?= GridView::widget([
    'dataProvider' => $DataProvider,
//    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'clicks',
        'registrations',
        'deposits',
        'MGR',
        'profit',
        'date:date',

        [
            'class' => 'yii\grid\ActionColumn',
            'buttons' => [
                'edit' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', \yii\helpers\Url::to(['@web/admin/referals/update-ref-det', 'id' => $model['id']]), ['title' => Yii::t('yii', 'Редактировать')]);
                },
//
//                'view' => function ($url, $model, $key)
//                { return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', \yii\helpers\Url::to(['@web/admin/referals/update-ref-det', 'id' => $model['id']]), ['title' => Yii::t('yii', 'Просмотреть')]);},


                'delete' => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', \yii\helpers\Url::to(['@web/admin/referals/del-ref-det', 'id' => $model['id']]), ['title' => Yii::t('yii', 'Удалить')]);
                },
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
            'template' => '{edit}&nbsp;&nbsp;{delete}',
        ],
    ],
]); ?>

