<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
//var_dump(get_class_methods('\yii\rbac\DbManager'));
// var_dump(Yii::$app->authManager->getUserIdsByRole('admin'));
$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]);?>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Права', ['rbac/permission'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Роли', ['rbac/role'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Назначить роли', ['rbac/assignment'], ['class' => 'btn btn-primary']) ?>
    </p>
       
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            'username',
            'img' => [
//                'class' => DataColumn::className(),
                'attribute' => 'avatar',
                'format' => 'image',
                'filter' => false,
            ],
            [
                'attribute' => 'id',
                'label' => 'id вирутального счета'
            ],
            [
                'attribute' => 'balance',
                'label' => 'Баланс'
            ],
            [
                'attribute' => 'commision',
                'label' => 'Коммисия пользователя',
                'content'=>function ($data) {
                    return $data->commision ? $data->commision : 'Стандартная';
                }
            ],
            'birthday',
//            'auth_key',
            // 'password_hash',
            // 'password_reset_token',
            'email:email',
//             'role',
            [
                'attribute' => 'roled',
                "label" => 'Роль',
                'filter' => ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description')+['user'=>'Юзер'],
                'value' => function ($data) {
                    $roles = implode(', ', ArrayHelper::map(Yii::$app->authManager->getRolesByUser($data->id), 'name', 'description'));
                    if (!$roles) {
                        $roles = 'Юзер';
                    }
                    return $roles;
                },
            ],
            // 'status',
            // 'eauth:ntext',
            // 'created_at',
            // 'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
