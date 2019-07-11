<?php
/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 15.02.2016
 * Time: 21:24
 */

use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\widgets\ListView;

if (count($childCat) > 0) {
    ?>
    <h3>Категории</h3>
    <?php foreach ($childCat as $cat) {
        ?>
        <div>
            <?= Html::a($cat['name'], Url::to(['/forum/category', 'id' => $cat['id_fcategory']])); ?>
        </div>
    <?php }
}?>

<p><?= Html::a("Создать тему",Url::to(['/forum/create/topic','id_cat'=>$id_category]))?></p>

<h4>Посты</h4>
<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '_topic-list'
]);
?>