<?php

use app\models\Bookmaker;
use app\models\Category;
use app\widgets\imgUpload\ImgUpload;
use vova07\imperavi\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\News */
/* @var $form yii\widgets\ActiveForm */
$category = ArrayHelper::map(Category::find()->asArray()->all(), 'id_category', 'name');
$bookmaker = ArrayHelper::map(Bookmaker::find()->asArray()->all(), 'id_bmaker', 'name');
array_unshift($bookmaker, "-");
$this->registerJsFile('/js/tags.js', ['depends' => ['yii\web\JqueryAsset', 'yii\jui\JuiAsset']]);
?>

<div class="news-form">

    <? //= ImgUpload::widget(['webUrl'=>"/uploads/images",'pathUrl'=>'@app/web/uploads/images'])?>


    <?php $form = ActiveForm::begin(); ?>


    <?= $form->field($model, 'title')->textInput() ?>

    <? //= $form->field($model, 'desc')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'desc')->widget(Widget::className(), [
        'settings' => [
            'lang' => 'ru',
            'minHeight' => 200,
            'plugins' => ['fontcolor']
        ]
    ]); ?>

    <? //= $form->field($model, 'text')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'text')->widget(Widget::className(), [
        'settings' => [
            'lang' => 'ru',
            'minHeight' => 200,
            'imageUpload' => Url::to(['/admin/settings/image-upload']),
            'imageManagerJson' => Url::to(['/admin/settings/images-get']),
            'plugins' => [
                'clips',
                'fullscreen',
                'imagemanager',
                'video',
                'fontcolor'
            ],
        ]
    ]); ?>

    <? //= $form->field($model, 'img')->textInput(['maxlength' => true]) ?>
    <? //= $form->field($model, 'img')->widget(\app\widgets\ImageUpload::className()) ?>
    <?= $form->field($model, 'img')->widget(ImgUpload::className(), [
        'webUrl' => "/uploads/images",
        'pathUrl' => '@app/web/uploads/images',
        'urlGet' => '/admin/settings/images',
        'urlUpload' => '/admin/settings/img-upload'
    ]) ?>

    <?= $form->field($model, 'id_category')->dropDownList($category) ?>

    <?= $form->field($model, 'id_bmaker')->dropDownList($bookmaker) ?>

    <?= $form->field($model, 'show_in_footer')->dropDownList(['right-block', 'up-block']) ?>

    <?= $form->field($model, 'sort')->textInput(['type' => 'number', 'value' => !empty($model->sort) ? $model->sort : 9]) ?>
        <div class="row" style="padding-bottom: 20px;">
            <div class="col-lg-6 col-md-6">
                <div class="input-group">
                    <input type="text" id="search_tag" placeholder="Введите тег" class="form-control" autocomplete="off">
                        <span class="input-group-btn">
                            <button class="btn btn-primary" type="button" style="text-transform: lowercase;" id="add_tag" disabled>Добавить</button>
                        </span>
                </div>
                <table id="tags-news">
                <?php if(!$model->isNewRecord){
                    foreach ($model->tags as $tag) { ?>
                        <tr>
                            <input type="hidden" name="tags[]" value="<?= $tag['tag'] ?>">
                            <td><?= $tag['tag'] ?></td>
                            <td>
                                <div class="btn btn-danger b-margin delete-tag">
                                    <i class="glyphicon glyphicon-remove"></i>
                                </div>
                            </td>
                        </tr>

                <?php } } ?>
                </table>
            </div>
        </div>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>


</div>


