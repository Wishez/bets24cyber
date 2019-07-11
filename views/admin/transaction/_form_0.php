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

    <?= $form->field($model, 'amount')->textInput(['type' => 'number', 'step' => '0.0000001', 'id' => 'amount'])->label('Сумма транзакции') ?>
    <?= $this->render('_currency') ?>

    <?= $form->field($model, 'commision')->textInput(['id' => 'commison'])->label('Комиссия') ?>

    <?= $form->field($model, 'note')->textArea(['rows' => 4])->label('Примечание') ?>

    <?= $form->field($model, 'agent')->textInput(['id' => 'user_field'])->label('Виртуальный счет') ?>
    
    <?= $form->field($model, 'partner')->textInput(['id' => 'fund_field'])->label('Фонд') ?>

    <?= $form->field($model, 'type')->textInput(['type' => 'hidden', 'value' => $type])->label(false) ?>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
