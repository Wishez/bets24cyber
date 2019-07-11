<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Банеры';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="banners-index">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('СОздать банер', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'img:image',
            'link:html',
            'alt_text:ntext',
            'active',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
