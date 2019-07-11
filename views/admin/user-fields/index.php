<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\models\UserFields;
/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Поля для заполнения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить поле', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => new ActiveDataProvider(
            [
                'query' => $model,
            ]
        ),
        'layout' => "{items}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'name',
                'label' => 'Название'
            ],
            [
                'attribute' => 'data_type',
                'label' => 'Тип Данных',
                'value' => function($model){
                    return UserFields::DataTypes()[$model->data_type];
                }
            ],
            [
                'attribute' => 'require_signup',
                'label' => 'Обязателен при регистрации',
                'value' => function($model){
                    return $model->require_signup == 1 ? 'Да' : 'Нет';
                }
            ],
            [
                'attribute' => 'require_pay',
                'label' => 'Обязателен при оплате',
                'value' => function($model){
                    return $model->require_pay == 1 ? 'Да' : 'Нет';
                }
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
