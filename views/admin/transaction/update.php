<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Редактирование транзакции';
$this->params['breadcrumbs'][] = ['label' => 'Поля', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('/js/transactions.js', ['depends' => ['yii\web\JqueryAsset', 'yii\jui\JuiAsset']]);

?>
<div class="user-create">

    <?= $this->render('_form_'.$model->type, [
        'model' => $model,
        'type' => $model->type
    ]) ?>

</div>
