<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\UserFields;
/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'amount')->textInput(['type' => 'number', 'step' => '0.0000001'])->label('Сумма транзакции') ?>

    <?= $form->field($model, 'commision')->textInput()->label('Комиссия') ?>

    <?= $form->field($model, 'note')->textArea(['rows' => 4])->label('Примечание') ?>

    <? //$form->field($model, 'agent')->textInput()->label('Фонд1') ?>
    

    <?= $form->field($model, 'type')->textInput(['type' => 'hidden', 'value' => $type])->label(false) ?>
    
    <?= $form->field($model, 'partner')->textInput()->label('Виртуальный счет') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
