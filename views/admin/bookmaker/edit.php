<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\News */

$this->title = 'Редактирование';


?>

<style type="text/css">
	.form-group{
		margin-bottom: 15px;
	}
</style>
<div class="news-update">
	<div class="row" style="padding-bottom: 20px;">
    <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>
    <div class="col-lg-12 col-md-12">
    	<div class="form-group">
         	<input type="text" class="form-control" name="name" disabled value="<?= $model['name'] ?>" placeholder="Название">
    	</div>  
    	 <div class="form-group">
         	<input type="file" class="form-control" name="img" value="<?= $model['img'] ?>" placeholder="Изображение">
    	</div>
    	 <div class="form-group">
         	<input type="text" class="form-control" name="link" value="<?= $model['link'] ?>" placeholder="Ссылка">
    	</div
    	<div class="form-group">
        	<?= Html::submitButton('Обновить', ['class' => 'btn btn-primary']) ?>
    	</div>
    </div>
    <?php ActiveForm::end(); ?>

    </div>
</div>

