<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\forum\models\ForumCategory;

/* @var $this yii\web\View */
/* @var $model app\modules\forum\models\ForumCategory */
/* @var $form yii\widgets\ActiveForm */
$category = ArrayHelper::map(ForumCategory::find()->asArray()->all(), 'id_fcategory', 'name');
?>

<div class="forum-category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_owner_category')->dropDownList($category) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
