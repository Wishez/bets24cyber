<?php
/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 17.06.2016
 * Time: 15:51
 */

use app\models\ReferelWallets;
use yii\grid\GridView;
$this->title = 'Парьнеры';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= GridView::widget([
    'dataProvider' => $referals,
//    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

//            'id',
        'username',
        [
            'attribute' => 'Кошелек',
            'value' => function ($model) {
                $wallet = ReferelWallets::findOne(['id_user' => $model->id]);

                return $wallet ? $wallet->wallet : "";
            }
        ],
//        'img' => [
////                'class' => DataColumn::className(),
//            'attribute' => 'avatar',
//            'format' => 'image',
//            'filter' => false,
//        ],
//        'birthday',
//            'auth_key',
        // 'password_hash',
        // 'password_reset_token',
        'email:email',
//             'role',
//        [
//            'attribute' => 'role',
//            "label" => 'Роль',
//            'filter' => array('admin' => "admin", 'user' => "user", "partner" => "partner"),
//        ],
        // 'status',
        // 'eauth:ntext',
        // 'created_at',
        // 'updated_at',

        ['class' => 'yii\grid\ActionColumn',
            'template' => '{view}',],
    ],
]); ?>