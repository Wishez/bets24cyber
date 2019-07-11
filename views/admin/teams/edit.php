<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;

/* @var $this yii\web\View */
/* @var $model app\models\News */

$this->title = 'Редактирование';
$this->registerJsFile('/js/teams_admin.js', ['depends' => ['yii\web\JqueryAsset', 'yii\jui\JuiAsset']]);

?>
<div class="news-update">
    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'team_id')->textInput(['placeholder' => 'id команды']) ?>
    <?= $form->field($model, 'name')->textInput(['placeholder' => 'Название']) ?>
    <?= $form->field($model, 'earning')->textInput(['placeholder' => 'Заработано']) ?>

    <?= $form->field($model, 'created')->textInput(['placeholder' => 'Основано']) ?>

    <?= $form->field($model, 'captain')->textInput(['placeholder' => 'Капитан']) ?>

    <?= $form->field($model, 'spec_id')->textInput(['placeholder' => ($model->game == 0 ? 'dotabuff' : 'hltv id')]) ?>

    <?php 

    foreach ($model->overview as $key => $overview) {
        $overview['text'] = htmlspecialchars_decode($overview['text']);  ?>
        
        <input type="hidden" name="text[<?= $key ?>][name]" value="<?= $overview['name'] ?>">
        <input type="hidden" name="text[<?= $key ?>][sort]" value="<?= $overview['sort'] ?>">
        <?php      
echo \vova07\imperavi\Widget::widget([
    'name' => 'text['.$key.'][text]',
    'value' => $overview['text'],
    'settings' => [
        'lang' => 'ru',
        'maxHeight' => 500,
        'plugins' => [
            'fontcolor'
        ]
    ]
]);


    }


    ?>



    <div class="row" style="padding-bottom: 20px;">
    <div class="col-lg-12 col-md-12">
    	<input type="text" id="search_tag" placeholder="Введите второе название" class="form-control" autocomplete="off" style="width: 300px; display: inline-block;">
        <button class="btn btn-primary" type="button" style="text-transform: lowercase; display: inline-block;" id="add_tag">Добавить</button>
    </div>
    <div class="col-lg-6 col-md-6">
    	        <table id="tags-news">
            <?php foreach ($model->names as $name) { ?>
                <tr>
                    <input type="hidden" name="names[]" value="<?= $name['name'] ?>">
                    <td><?= $name['name'] ?></td>
                    <td>
                        <div class="btn btn-danger b-margin delete-tag">
                            <i class="glyphicon glyphicon-remove"></i>
                        </div>
                    </td>
                </tr>
        <?php } ?>
        </table>
    </div>
</div>


    <div class="form-group">
        <?= Html::submitButton('Обновить', ['btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>


</div>




</div>
