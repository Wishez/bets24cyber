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

    <?= $form->field($model, 'name')->textInput()->label('Название фонда') ?>
    <? //$form->field($model, 'name')->textInput()->label('Название') ?>

<!--    --><?//= $form->field($model, 'status')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
