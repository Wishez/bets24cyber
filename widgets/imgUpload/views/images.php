<?php

//var_dump($images);
use yii\widgets\ListView;
use yii\widgets\Pjax;

$dataProvider = new \yii\data\ArrayDataProvider([
    'allModels' => $images,
    'pagination' => [
        'pageSize' => 10,
    ],
]);
echo ListView::widget([
    'dataProvider' => $dataProvider,
    'layout' => "{summary}\n<div class='items'>{items}</div>\n<div class='pagin'>{pager}</div>",
    'itemOptions' => [
        'tag' => 'span',
        'class' => 'image-item'
    ],
    'itemView' => "_image",
     /*   function ($model, $key, $index, $widget) {

        return "<img src='" . $model['image'] . "' title='" . $model['title'] . "'>";
    }*/
]);

?>

<!--<div class="image-files">
    <?php /*foreach ($images as $image) {
        echo "<img src='" . $image['image'] . "' title='" . $image['title'] . "'>";
    } */ ?>
</div>
-->