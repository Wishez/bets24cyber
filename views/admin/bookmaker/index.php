<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel app\models\BookmakerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Букмекеры';
$this->params['breadcrumbs'][] = $this->title;

?>

<?php if($work == 1){ ?>
    <a class="btn btn-danger" href="/admin/bookmaker/update">Отключить</a>
<?php }else{ ?>
    <a class="btn btn-success" href="/admin/bookmaker/update">Включить</a>
<?php } ?>
<div class="bookmaker-index">

<?= GridView::widget([
    'dataProvider' => new ArrayDataProvider(
            [
                'allModels' => $model,            ]
        ),
        'tableOptions' => ['class' => 'table table-striped table-bordered', 'style' => 'background-color: #fff;'],
    'summaryOptions' => [
    'tag' => 'p'
    ],
    'columns' =>
    [
        'img' => [
            'label' => 'Лого',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::img($model['img'], ['style' => 'background-color: #000; height: 35px;']);
            }            
        ],
        'name' => [
            'label' => 'БК',
            'format' => 'raw',
            'value' => function ($model) {
                return $model['name'];
            }          
        ],
        'link' => [
            'label' => 'Ссылка',
            'format' => 'raw',
            'value' => function ($model) {
                return $model['link'];   
            }            
        ],
        'do' => [
            'label' => 'Действия',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::a('', Url::to(['admin/bookmaker/edit', 'id' => $model['id']]), ['class' => 'glyphicon glyphicon-pencil']); 
            }            
        ],
    ]
    ]);
?>
 
</div>
