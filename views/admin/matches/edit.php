<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\News */

$this->title = 'Редактирование';
$this->registerJsFile('/js/ma.js', ['depends' => ['yii\web\JqueryAsset', 'yii\jui\JuiAsset']]);
?>
<div class="news-update">

    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'team1')->textInput(['placeholder' => 'Команда 1']) ?>
    <?= $form->field($model, 'team2')->textInput(['placeholder' => 'Команда2']) ?>
    <?= $form->field($model, 'date')->textInput(['placeholder' => 'Дата']) ?>

    <div class="row" style="padding-bottom: 20px;">
    <div class="col-lg-12 col-md-12">
    	<input type="text" id="search_tag" placeholder="Введите канал twitch" class="form-control" autocomplete="off" style="width: 250px; display: inline-block;">
        <input type="text" id="search_c" placeholder="Введите страну" class="form-control" autocomplete="off" style="width: 150px; display: inline-block;">
        <button class="btn btn-primary" type="button" style="text-transform: lowercase; display: inline-block;" id="add_tag">Добавить</button>
    </div>
    <div class="col-lg-6 col-md-6">
    	        <table id="tags-news">
            <?php foreach ($model->streams as $key => $stream) { ?>
                <tr>
                    <input type="hidden" name="streams[<?= $key ?>][channel]" value="<?= $stream['channel'] ?>">
                    <td><?= $stream['channel'] ?></td>

                    <input type="hidden" name="streams[<?= $key ?>][country]" value="<?= $stream['country'] ?>">
                    <td><?= $stream['country'] ?></td>
                    <td>
                        <label style="font-weight: 100; margin-top: 4px">
                            <input type="checkbox" value="1" <?= $stream['sort'] == 1 ? 'checked' : '' ?> name="streams[<?= $key ?>][main]" style="position: relative; top: 2px;" class="main-check"> 
                        Главный</label>
                    </td>
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
        <?= Html::submitButton('Обновить', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>


</div>




</div>
