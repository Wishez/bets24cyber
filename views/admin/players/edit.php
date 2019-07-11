<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use vova07\imperavi\Widget;

/* @var $this yii\web\View */
/* @var $model app\models\News */

$this->title = 'Редактирование';

?>
<div class="news-update">
    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'player_id')->textInput(['placeholder' => 'Ник']) ?>

    <?= $form->field($model, 'name')->textInput(['placeholder' => 'Имя']) ?>

    <?= $form->field($model, 'earning')->textInput(['placeholder' => 'Заработано']) ?>

    <?= $form->field($model, 'age')->textInput(['placeholder' => 'Возраст']) ?>

    <?= $form->field($model, 'role')->textInput(['placeholder' => 'Роль']) ?>

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



    <div class="form-group">
        <?= Html::submitButton('Обновить', ['btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>


</div>




</div>
