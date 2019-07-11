<?php
/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 17.06.2016
 * Time: 16:16
 */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

//var_dump($referal_url);
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'ref_url') ?>


<?= $form->field($model, 'user_id')->hiddenInput(['value'=>$user_id])->label(false) ?>

<!--    --><? //= $form->field($model, 'status')->textInput() ?>


<div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>

</div>