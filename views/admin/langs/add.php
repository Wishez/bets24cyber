<?
use yii\helpers\Html;

use yii\widgets\ActiveForm;


Yii::$app->cache->flush() ;

$this->title = 'Add Lang';
?>
<!-- <div class="row">
	
</div> -->
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
<div  class="panel panel-default">
	<div class="panel-heading">

		<span class="elipsis"><!-- panel title -->
			<strong>Main</strong>
		</span>

		<!-- right options -->
		<ul class="options pull-right list-inline">
			<li><a href="#" class="opt panel_colapse" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Colapse"></a></li>
		</ul>
		<!-- /right options -->

	</div>
	<!-- panel content -->
	<div class="panel-body">
	<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
	<?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>
	<?= $form->field($model, 'local')->textInput(['maxlength' => true]) ?>
	



	


	</div>
	<!-- /panel footer -->
</div>





<div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>