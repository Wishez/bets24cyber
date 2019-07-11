<?php
use yii\helpers\Html;

use yii\widgets\ActiveForm;

Yii::$app->cache->flush() ;

$this->title = 'Edit Lang';
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

<div  class="panel panel-default">
	<div class="panel-heading">

		<span class="elipsis"><!-- panel title -->
			<strong>Translates</strong>
		</span>

		<!-- right options -->
		<ul class="options pull-right list-inline">
			<li><a href="#" class="opt panel_colapse" data-toggle="tooltip" title="" data-placement="bottom" data-original-title="Colapse"></a></li>
		</ul>
		<!-- /right options -->

	</div>
	<!-- panel content -->
	<div class="panel-body ">
	<div class="box-tab left">
	  <ul class="nav nav-tabs">
	  <li>SITE</li>
	  <hr>
	  <?foreach ($pages as $pkey => $name) {?>
	  	<li class="<?if($pkey=='main'){?>active<?}?>"><a href="#<?=$pkey?>" data-toggle="tab"><?=$name?></a>
	    </li>
	  <?}?>
	  </ul>
	  <div class="tab-content">
	  <?foreach ($pages as $pkey => $name) {?>

	    <div class="tab-pane <?if($pkey=='main'){?>active in<?}?>" id="<?=$pkey?>">
	      <h3 ><?=$name?></h3>
	      <div class="trans">
	      	
	      	<div class="row">
				<div class="col-md-6">Translete Key</div>
				<div class="col-md-6">Translete Value</div>
			</div>
			<?php
            foreach ($trans[$pkey] as $key => $value) {
                ?>
			<div class="row">
				<div class="col-md-6"><input type="text" class="form-control" name="trans[<?=$pkey?>][key][]" value="<?=$key?>" disabled><input type="hidden" class="form-control" name="trans[<?=$pkey?>][key][]" value="<?=$key?>" ></div>
				<div class="col-md-6"><input type="text" class="form-control" name="trans[<?=$pkey?>][val][]" value="<?=$value?>"></div>
			</div>
			<br>	
			<?php
            }?>
	      </div>
	      <button type="button" data-key='<?=$pkey?>' class="add_row btn btn-success">Add Row</button>
	    </div>
	  <?}?>
	    
	  </div>
	</div>
	


	


	</div>
	<div class="panel-footer">
		
	</div>
	
	<!-- /panel footer -->
</div>


<script type="text/javascript">
	
</script>




<div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>
<?php
$this->registerJs(
      "
		$('body').on('click', '.add_row',function(e){
        var key = $(this).data('key');
        $(this).closest('.tab-pane').find('.trans').append('<div class=\"row\"><div class=\"col-md-6\"><input type=\"text\" class=\"form-control\" name=\"trans['+key+'][key][]\" value=\"\"></div><div class=\"col-md-6\"><input type=\"text\" class=\"form-control\" name=\"trans['+key+'][val][]\" value=\"\"></div></div><br>')
    });

    "
    );?>