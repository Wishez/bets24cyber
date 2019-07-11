<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Orders;
use app\models\User;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders for Match #'.$model->id.' : '.$model->teamOne->name.' - '.$model->teamTwo->name;
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="orders-index">

    <h1><?= Html::encode($this->title) ?>. Время приема ставок увеличено на <?=$model->orders_time?> минут</h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?php if (count($model->getOrders()->where(['status'=>3])->all())): ?>

        <?= Html::a('Переработать', ['change', 'id'=>$model->id], ['class' => 'btn btn-danger']) ?>  
        <?php endif;  ?>
        <?php if (count($model->getOrders()->innerJoinWith('pays')->andWhere(['transactions.status'=>[0]])->all())&&count($model->getOrders()->where(['status'=>3])->all())):  ?>
        <?= Html::a('Подтвердить', ['success', 'id'=>$model->id], ['class' => 'btn btn-success']) ?> 
        <?php endif;  ?>
        <?php //if (!$model->gameOver): ?>
            <?= Html::a('Продлить прием на 1мин', ['add-time','id'=>$model->id,'time'=>1], ['class' => 'btn btn-info']) ?>
            <?= Html::a('Продлить прием на 5мин', ['add-time','id'=>$model->id,'time'=>5 ], ['class' => 'btn btn-info']) ?>
            <?= Html::a('Продлить прием на 10мин', ['add-time','id'=>$model->id,'time'=>10 ], ['class' => 'btn btn-info']) ?>
            <?php if ($model->orders_active): ?>
                <?= Html::a('Закрыть прием', ['getting', 'id'=>$model->id], ['class' => 'btn btn-danger']) ?>
            <?php else :  ?>
                <?= Html::a('Открыть прием', ['getting', 'id'=>$model->id], ['class' => 'btn btn-success']) ?>
            <?php endif;  ?>
            
           
        <?php //endif ?>
        
    </p>
    <?php if (Yii::$app->user->can('возможность изменить победителя матча')): ?>
        <p>
        Счет Матча
        </p>
        <div class="row">
        <?php $form = ActiveForm::begin(['action'=>['score', 'id'=>$model->id]]); ?>
            <div class="col-md-2"> <?= $form->field($model, 'score_left')->textInput(['type'=>'number'])->label(false) ?></div>
            <div class="col-md-2"><?= $form->field($model, 'score_right')->textInput(['type'=>'number'])->label(false) ?></div>
            <div class="col-md-2"><?= Html::submitButton( 'Update', ['class' =>  'btn btn-success' ]) ?></div>
         <?php ActiveForm::end(); ?>
        </div>
    <?php endif ?>
       
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{pager}\n{summary}\n{items}\n{summary}\n{pager}",
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            //'match_id',
             [
                'attribute'=>'author_id',
                'format'=>'text', // Возможные варианты: raw, html
                'content'=>function($data){
                    return User::findOne($data->author_id)->email.' (# '.$data->author_id.')';
                },
            ],
            'summ',
            //'orderTeam.name',
            [
                'attribute'=>'winner',
                'format'=>'text', // Возможные варианты: raw, html
                // 'content'=>function($data){
                //     return $data->statusName;
                // },
                'filter' => [1=>1,2=>2]
            ],
            [
                'attribute'=>'status',
                'format'=>'text', // Возможные варианты: raw, html
                'content'=>function($data){
                    return $data->statusName.($data->getPays()->where(['transactions.status'=>0])->count() ? '(В процессе)' : '');
                },
                'filter' => Orders::getStatuses()
            ],
            'rate',
            [
                'attribute' => 'date',
                'format' =>  ['date', 'HH:mm:ss dd.MM.YYYY'],
                'options' => ['width' => '200']
            ],
            [
                'attribute' => 'up_date',
                'format' =>  ['date', 'HH:mm:ss dd.MM.YYYY'],
                'options' => ['width' => '200']
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}{transactions}',
                'options' => ['width' => '300'],
                'buttons' => [

                    'view' => function ($url,$model) {
                        return Html::a(
                        '<i class="icon fa fa-bars"></i> Логи', 
                        $url,
                        ['class' => 'btn btn-info btn-rounded']
                        );
                    },
                    'transactions' => function ($url,$model) {
                        return Html::a(
                        '<i class="icon fa fa-bars"></i> Транзакции', 
                        ['admin/transaction', 'id'=>$model->id],
                        ['class' => 'btn btn-success btn-rounded']
                        );
                    },
                ],
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
