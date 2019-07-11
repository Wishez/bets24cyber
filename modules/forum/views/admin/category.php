<?php
/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 16.02.2016
 * Time: 23:03
 */
use yii\grid\GridView;

echo "category";
?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
//    'filterModel' => $searchModel,
//    'layout' => "{pager}\n{summary}\n{items}\n{summary}\n{pager}",
    'columns' => [
//        ['class' => 'yii\grid\SerialColumn'],
        'id_fcategory',
        'name',
        'id_owner_category'
    ]
])

?>