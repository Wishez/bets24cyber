<?php

use yii\grid\DataColumn;
use yii\helpers\Html;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel app\models\NewsSearch */

$this->title = 'Теги';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tags-index" style="padding: 0px 20px;">
    <div class="row">
        <form action="" method="post">
        <?php
    $form = ActiveForm::begin([
        'method' => 'post'
    ]); 

        ?>
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Введите тег" name="tag">
                    <span class="input-group-btn">
                        <input type="submit" class="btn btn-primary" type="button" style="text-transform: lowercase;" value="Добавить">
                    </span>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
    <div class="row">
<?php
echo GridView::widget([
        'dataProvider' => new ArrayDataProvider([
                'allModels' => $model,
                'pagination' => [
                    'pageSize' => 40,
                    'pageParam' => 'page',
                    'pageSizeParam' => 'per-page'
                ],
            ]),
        'layout' => "{items}\n{summary}\n{pager}",
        'columns' => [
            'tag' => [
                    'label' => 'Тег',
                    'format' => 'raw',
                    'value' => function ($model) {
                            return $model['tag'];
                        }                 
                ],
            'num' => [
                    'label' => 'Упоминания',
                    'format' => 'raw',
                    'value' => function($model){
                        return '0';
                    }           
                ],
        ],
    ]);


?>
    </div>
</div>


