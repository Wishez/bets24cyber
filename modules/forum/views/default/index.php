<?php
use yii\bootstrap\Html;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ListView;

$this->params['class_page'] = 'forum_categorys';
?>

<?php
/*$dataProvider = new ArrayDataProvider([
    'allModels' => $categorys,
]);
echo ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '_category-list'
]);*/
?>
<?php
$cats = ArrayHelper::map($categorys, 'id_fcategory', 'name', 'id_owner_category');
foreach ($cats[0] as $key => $c) { ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <?= Html::a($c, Url::to(['/forum/category', 'id' => $key])); ?>
        </div>
        <div class="panel-body">
            <?php if (key_exists($key, $cats)) {
                foreach ($cats[$key] as $key2 => $c2) { ?>
                    <div>
                        <?= Html::a($c2, Url::to(['/forum/category', 'id' => $key2])); ?>
                    </div>
                <?php }
            } ?>
        </div>
    </div>
<?php }
?>


