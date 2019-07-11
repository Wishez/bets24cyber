<?php
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

$form = ActiveForm::begin([
    'id' => 'login-form',
    'action'=>\yii\helpers\Url::to(['site/login']),
//    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
//        'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
        'labelOptions' => ['class' => 'col-lg-1 control-label'],
    ],
]); ?>

<?= $form->field($model, 'email')->textInput(['id' => 'inputEmail','placeholder'=>'e-mail'])->label(false) ?>

<?= $form->field($model, 'password')->passwordInput(['id'=>'inputPassword','placeholder'=>'пароль'])->label(false) ?>

<? /*= $form->field($model, 'rememberMe')->checkbox([
    'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
]) */ ?>

            <?= Html::submitButton('Вход', ['class' => 'button', 'name' => 'login-button']) ?>

<?php ActiveForm::end(); ?>