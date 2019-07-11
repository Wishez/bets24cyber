<?php
/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 15.02.2016
 * Time: 21:24
 */

use yii\data\ActiveDataProvider;
use yii\widgets\ListView;


echo ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView'=> '_post-list'
]);
?>