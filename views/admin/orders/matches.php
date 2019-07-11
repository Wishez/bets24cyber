<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Orders;
use app\models\Transaction;
/* @var $this yii\web\View */
/* @var $searchModel app\models\OrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Matches';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?//= Html::a('Create Orders', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{pager}\n{summary}\n{items}\n{summary}\n{pager}",
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            'team1',
            'team2',
            'score_left',
            'score_right',
            //'orderTeam.name',
            // [
            //     'attribute'=>'winner',
            //     'format'=>'text', // Возможные варианты: raw, html
            //     // 'content'=>function($data){
            //     //     return $data->statusName;
            //     // },
            //     'filter' => [1=>1,2=>2]
            // ],
            // [
            //     'attribute'=>'status',
            //     'format'=>'text', // Возможные варианты: raw, html
            //     'content'=>function($data){
            //         return $data->statusName;
            //     },
            //     'filter' => Orders::getStatuses()
            // ],
            // 
            // 
            // [
            //     'label' => 'Закончен',
            //     //'format' =>  ['date', 'HH:mm:ss dd.MM.YYYY'],
            //     // 'options' => ['width' => '200']
            //     'content'=>function($data){
            //         return $data->gameOver ? 'Да' : 'Нет';
            //     },
            // ],
            [   
                'attribute'=>'is_succes',
                'label'=>'Требует Подтвеждения',
                'format'=>'text', // Возможные варианты: raw, html
                'content'=>function($data){
                    $ids = ArrayHelper::map($data->orders, 'id', 'id');
                    $tr_count = Transaction::find()->where(['type'=>3, 'status'=>0, 'agent'=>$ids])->count();
                    return $tr_count ? 'Да' : 'Нет';
                },
                'filter' => [0=>'Да', 1=>'Нет']
            ],
            
            [
                'label'=>'Orders Count',
                'format'=>'text', // Возможные варианты: raw, html
                'content'=>function($data){
                    return $data->getOrders()->count();
                },
                //'filter' => Orders::getStatuses()
            ],
           
           
            [
                'attribute' => 'date',
                'format' =>  ['date', 'HH:mm:ss dd.MM.YYYY'],
                'options' => ['width' => '200']
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width' => '300'],
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url,$model) {
                        return Html::a(
                        '<i class="icon fa fa-bars"></i> Ордеры', 
                        ['orders-list' , 'id'=>$model->id],
                        ['class' => 'btn btn-success btn-rounded']
                        );
                    },
                    
                ],
            ],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
