<?php

use yii\grid\DataColumn;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel app\models\NewsSearch */

$this->title = 'Новости';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">

    <p>
        <?= Html::a('Создать новость', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{pager}\n{summary}\n{items}\n{summary}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id_news',
            'title:ntext',
//            'desc:ntext',
//            'text:ntext',
            'img' => [
                'class' => DataColumn::className(),
                'attribute' => 'img',
                'format' => 'html',
                'filter' => false,
                'value' => function ($model) {
                    return $model->img ? '<img src="' . $model->img . '" alt="' . $model->title . '" width="100">' : "";
                }
            ],
/*            [
                'attribute' => 'id_user',
                "label" => 'Имя Автора',
//                'filter'=>true,
                'value' => 'user.username',
            ],*/
            [
                'attribute' => 'id_category',
                "label" => 'Категория',
                'value' => 'category.name',
            ],
            [
                'attribute' => 'show_in_footer',
                "label" => 'Место',
                'value' => function ($model) {
                    return $model['show_in_footer'] == 0 ? 'right-block' : 'up-block';
                }
//                'value' => 'bookmaker.name',
            ],
//            'show_in_footer:boolean',
            'created_at:datetime',
            'updated_at:datetime',
            'sort',

            [
             'class' => 'yii\grid\ActionColumn',
             'buttons' =>
                 [

                 ],
             'template' => '{view}&nbsp;&nbsp;{update}&nbsp;&nbsp;{delete}',
            ],



        ],
    ]); ?>

</div>


