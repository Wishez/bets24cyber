<?php
/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 17.06.2016
 * Time: 17:47
 */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use kartik\date\DatePicker;

//var_dump($referal_url);
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'clicks') ?>
    <?= $form->field($model, 'registrations') ?>
    <?= $form->field($model, 'deposits') ?>
    <?= $form->field($model, 'MGR') ?>
    <?= $form->field($model, 'profit') ?>
    <?= $form->field($model, 'date')->widget(DatePicker::className(), [
        'removeButton' => false,
        'type' => DatePicker::TYPE_COMPONENT_APPEND,
        'size' => 'sm',
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ])  ?>


    <?= $form->field($model, 'id_ref')->hiddenInput(['value'=>$id_ref])->label(false) ?>

    <!--    --><? //= $form->field($model, 'status')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>