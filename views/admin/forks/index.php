<?php

use yii\grid\DataColumn;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\models\Fork;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel app\models\NewsSearch */

$this->title = 'Вилки';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('/js/add_email.js', ['depends' => ['yii\web\JqueryAsset', 'yii\jui\JuiAsset']]);

?>

<?php
echo GridView::widget([
        'dataProvider' => new \yii\data\ActiveDataProvider(
            [
                'query' => $model,
                'pagination' => [
                    'pageSize' => 15,
                ],
            ]
        ),        'layout' => "{items}\n{pager}",
        'options' => ['style' => 'background-color: #fff'],
        'columns' => [

            'game' => [
                    'label' => 'Матч',
                    'format' => 'raw',
                    'value' => function($model){
                        return '<a href="'.Url::to(['site/match-details', 'id' => $model->match_id]).'">Матч</a>';
                    }           
                ],
            'profit' => [
                    'label' => 'Прибыль',
                    'format' => 'raw',
                    'value' => function($model){
                        return round($model->profit, 2).'%';
                    }           
                ],
            'bk1' => [
                    'label' => 'БК1',
                    'format' => 'raw',
                    'value' => function($model){
                        return '<a href="'.$model->link1.'">БК1</a>';

                    }           
                ],
            'bk2' => [
                    'label' => 'БК2',
                    'format' => 'raw',
                    'value' => function($model){
                        return '<a href="'.$model->link2.'">БК2</a>';
                    }           
                ],
            'date' => [
                    'label' => 'Время',
                    'format' => 'raw',
                    'value' => function($model){
                        return $model->date;
                    }           
                ],
        ],
    ]);


?>
<?php $form = ActiveForm::begin(); ?>
<div class="row" style="padding-bottom: 20px;">
    <div class="col-lg-6 col-md-6">
        <div class="input-group">
            <input type="text" id="search_tag" placeholder="Введите почту" class="form-control" autocomplete="off">
                <span class="input-group-btn">
                    <button class="btn btn-primary" type="button" style="text-transform: lowercase;" id="add_tag">Добавить</button>
                </span>
        </div>
        <table id="tags-news">
        <?php 
            foreach (Fork::getEmail() as $email) { ?>
                <tr>
                    <input type="hidden" name="tags[]" value="<?= $email['email'] ?>">
                    <td><?= $email['email'] ?></td>
                    <td>
                        <div class="btn btn-danger b-margin delete-tag">
                            <i class="glyphicon glyphicon-remove"></i>
                        </div>
                    </td>
                </tr>

        <?php }  ?>
        </table>
    </div>

</div>
<button class="btn btn-primary">Сохранить</button>
<?php ActiveForm::end(); ?>



