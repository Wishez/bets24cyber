<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use app\models\Transaction;
use app\models\User;
/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Транзакции';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Пополнение', ['create?type=0'], ['class' => 'btn btn-primary']) ?>
        
        <?= Html::a('Возврат клиенту', ['create?type=1'], ['class' => 'btn btn-primary']) ?>

        <?= Html::a('Оплата ордера', ['create?type=4'], ['class' => 'btn btn-primary']) ?>

        <?= Html::a('Перевод из фонда в фонд', ['create?type=2'], ['class' => 'btn btn-primary']) ?>

        <?= Html::a('Выплата', ['create?type=3'], ['class' => 'btn btn-primary']) ?>

    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{pager}\n{summary}\n{items}\n{summary}\n{pager}",
        'columns' => [
            'id' => [
                'attribute' => 'id',
                'label' => 'id'
            ],
            [
                'attribute' => 'date',
                'label' => 'Дата'
            ],
            [
                'attribute' => 'type',
                'label' => 'Тип',
                'value' => function($model){
                    return $model->typeText;
                }
            ],
            [
                'attribute' => 'status',
                'label' => 'Статус',
                'value' => function($model){
                    return $model->statusText;
                },
                'filter' => Transaction::getStatuses()
            ],
            'amount' => [
                'attribute' => 'amount',
                'label' => 'Сумма'
            ],
            [
                'attribute' => 'commision',
                'label' => 'Комиссия',
                
            ],
            [
                'attribute' => 'virt',
                'label' => 'Виртуальный счет',
                'value' => function($model){
                    if ($model->type==Transaction::TYPE_REFIll||$model->type==Transaction::TYPE_ORDER) {
                        $virt = User::findOne($model->agent)->email.' (#'.$model->agent.')';

                    }
                    elseif ($model->type==Transaction::TYPE_RETURN||$model->type==Transaction::TYPE_PAY) {
                        $virt = User::findOne($model->partner)->email.' (#'.$model->partner.')';
                    }
                    else $virt = 'none';
                    return $virt;
                }
            ],
            [
                'attribute' => 'fund',
                'label' => 'Фонд',
                'value' => function($model){
                    if ($model->type==Transaction::TYPE_RETURN||$model->type==Transaction::TYPE_FUND) {
                        $fund = $model->agent;

                    }
                    elseif ($model->type==Transaction::TYPE_REFIll) {
                        $fund = User::findOne($model->partner)->email.' (#'.$model->partner.')';
                    }
                    else $fund = 'none';
                    return $fund;
                }
            ],
            [
                'label' => 'Тело',
                'value' => function($model){
                    return $model->body;
                }
            ],
            [
                'attribute' => 'note',
                'label' => 'Примечание'
            ],
            [
                'format' => 'raw',
                'value' => function($model){
                    if($model->status == Transaction::STATUS_PROCCES){
                    return '<a href="/admin/transaction/update?id='.$model->id.'" title="Редактировать" aria-label="Редактировать" data-pjax="0">
                        <span class="glyphicon glyphicon-pencil btn btn-primary btn-xs"></span>
                    </a>
                    <a href="/admin/transaction/go?id='.$model->id.'" title="Выполнить" aria-label="Выполнить" data-pjax="0">
                        <span class="glyphicon glyphicon-ok btn btn-success btn-xs"></span>
                    </a>
                    <a href="/admin/transaction/disable?id='.$model->id.'" title="Отменить" aria-label="Отменить" data-pjax="0">
                        <span class="glyphicon glyphicon-remove btn btn-danger btn-xs"></span>
                    </a>';
                    }
                    return '<a href="/admin/transaction/disable?id='.$model->id.'" title="Отменить" aria-label="Отменить" data-pjax="0">
                        <span class="glyphicon glyphicon-remove btn btn-danger btn-xs"></span>
                    </a>';
                }
            ]
        ],
    ]); ?>
</div>
