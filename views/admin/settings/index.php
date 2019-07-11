<?php
/* @var $this yii\web\View */
use app\widgets\MultipleInput;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;

$this->registerJsFile('/js/jquery.wallform.js', ['depends' => 'yii\web\JqueryAsset']);
$this->registerJsFile('/js/script.js', ['depends' => ['yii\web\JqueryAsset', 'yii\jui\JuiAsset']]);
$this->title = "Настройки";
?>
<?php if (Yii::$app->user->can('изменение комиссии сайта')): ?>
    <div class="panel panel-primary">
        <div class="panel-heading">Настройки системы</div>
        <div class="panel-body">
            <?php $form = ActiveForm::begin(); ?>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Коммисия</label>
                        <input type="number" class="form-control" id="exampleInputEmail1" placeholder="Коммисия в процентах" name="settings[MAIN_SITE_COMMISSION]" value="<?= $settings['MAIN_SITE_COMMISSION'] ?>">
                    </div>


                    <div class="form-group">
                        <label for="timeInput">Часовой пояс</label>
                        <select  class="form-control" id="timeInput" name="settings[MAIN_SITE_TIMEZONE]">
                            <?php foreach ($timezones as $key => $zone): ?>
                                <option <?if($settings['MAIN_SITE_TIMEZONE']==$key){?>selected<?}?> value="<?=$key?>"><?=$zone?></option>
                            <?php endforeach ?>

                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
<?php endif ?>


<?php if (Yii::$app->user->can('изменение комиссии сайта')): ?>
<div class="panel panel-primary">
    <div class="panel-heading">Header меню</div>
    <div class="panel-body">

        <?php $form = ActiveForm::begin(['options' => ['id' => 'headerMenu']]); ?>
        <div class="row">
            <div class="header_menu col-lg-6">

                <?= $form->field($header_menu, 'header_menu')->widget(MultipleInput::className(), [
                    'min' => 0,
                    'limit' => 10,
                    'allowEmptyList' => false,
                    'enableGuessTitle' => true,
                    'addButtonOptions' => [
                        'class' => 'btn btn-success',
                        'label' => '<i class="glyphicon glyphicon-plus"></i>', // also you can use html code
                    ],
                    'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header
                    'columns' => [
                        [
                            'name' => 'name',
                            'title' => 'Имя',
                            'options' => [
                                'placeholder' => 'Имя',
                                'title' => 'Имя'
                            ],
                        ],
                        [
                            'name' => 'url',
                            'title' => 'URL',
                            'options' => [
                                'placeholder' => 'URL',
                                'title' => 'URL'
                            ],
                        ],
                    ]
                ])->label(false); ?>
            </div>
        </div>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>

        <?php ActiveForm::end(); ?>

    </div>
</div>
<?php endif ?>


