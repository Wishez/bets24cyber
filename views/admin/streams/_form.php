<?php
use kartik\datetime\DateTimePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\widgets\imgUpload\ImgUpload;
?>
<div class="top-streams-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?php
    //2016-02-10 00:00:00
    //2016-02-02 07:16:10

    echo '<label class="control-label">Дата старта стрима</label>';

    echo $form->field($model, 'date')->widget(DateTimePicker::className(),
    [
        'name' => 'date',
        'type' => DateTimePicker::TYPE_COMPONENT_PREPEND,
        'value' => '',
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd hh:ii:ss'
        ]
    ])->label(false);
    ?>
    <?= $form->field($model, 'views')->textInput() ?>

    <?= $form->field($model, 'likes')->textInput() ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

    <?//= $form->field($model, 'img')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'img')->widget(ImgUpload::className(), [
        'webUrl' => "/uploads/images",
        'pathUrl' => '@app/web/uploads/images',
        'urlGet' => '/admin/settings/images',
        'urlUpload' => '/admin/settings/img-upload'
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
