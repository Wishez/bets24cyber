<?php
/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 15.02.2016
 * Time: 21:14
 */
use yii\bootstrap\Html;
use yii\helpers\Url;

?>

<?= Html::a($model['name'],Url::to(['/forum/category','id'=>$key]))?>
