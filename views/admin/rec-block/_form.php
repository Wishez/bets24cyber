<?php

use app\widgets\imgUpload\ImgUpload;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Banners */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="banners-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'img')->widget(ImgUpload::className(), [
        'webUrl' => "/uploads/images/banners",
        'pathUrl' => '@app/web/uploads/images/banners',
        'urlGet' => '/admin/rec-block/images',
        'urlUpload' => '/admin/rec-block/img-upload'
    ]) ?>

    <?= $form->field($model, 'link')->textInput() ?>

    <?= $form->field($model, 'alt_text')->textInput() ?>

    <?= $form->field($model, 'active')->dropDownList(['1'=>'Активный','0'=>'Не активный']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
