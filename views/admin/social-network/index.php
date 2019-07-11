<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SocialNetworkSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Социальная сеть';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="social-network-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Создать социальную сеть', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    </br>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'logo',
            'href',

            [
                'class' => \yii\grid\ActionColumn::className(),
                'buttons' => [
                    'view',
                    'update',
                    'delete',
                    'visible'=> function ($url, $model, $key)
                    {
                        if ($model->enable == 1)
                        {
                            return Html::a('<span class="glyphicon glyphicon-check"></span>', \yii\helpers\Url::to(['visible', 'id' => $model['id_snetwork'],'v'=> 0]), ['title' => 'Скрыть']);
                        }
                        else
                        {
                            return Html::a('<span class="glyphicon glyphicon-unchecked"></span>', \yii\helpers\Url::to(['visible', 'id' => $model['id_snetwork'],'v'=>1]), ['title' => 'Показать']);
                        }
                    },
                ],
                'template' => '{view}&nbsp;&nbsp;{update}&nbsp;&nbsp;{delete}&nbsp;&nbsp;{visible}',
            ]


        ],
    ]); ?>

</div>
