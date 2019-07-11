<?php
/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 23.02.2016
 * Time: 16:40
 */
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

echo $id_cat;
?>

<?php $form = ActiveForm::begin(); ?>
<?= $form->field($modelTopic, 'id_fcategory')->hiddenInput(['value'=>$id_cat]) ?>
<?= $form->field($modelTopic, 'name')->textInput(['maxlength' => true]) ?>
<?= $form->field($modelPost, 'description')->textarea() ?>
<?= Html::submitButton('Создать тему')?>

<?php ActiveForm::end(); ?>
