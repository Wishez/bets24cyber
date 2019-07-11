<?php

use app\modules\forum\models\ForumCategory;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\forum\models\ForumTopic */
/* @var $form yii\widgets\ActiveForm */
$category = ArrayHelper::map(ForumCategory::find()->asArray()->all(), 'id_fcategory', 'name');
?>

<div class="forum-topic-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_fcategory')->dropDownList($category)?>

    <?= $form->field($model, 'enable')->checkbox() ?>

    <?= $form->field($model, 'visible')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
