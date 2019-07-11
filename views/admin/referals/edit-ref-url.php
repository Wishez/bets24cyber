<?php
/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 17.06.2016
 * Time: 17:03
 */

$this->title = "Редактирование реферальной ссылки";
$this->params['breadcrumbs'][] = ['label' => 'Партнеры', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'реферальные ссылки', 'url' => ['view','id'=>$user_id]];
$this->params['breadcrumbs'][] = ''
?>
<div class="ref-edit">


    <?= $this->render('_form', [
    'model' => $referal_url,
    'user_id' => $user_id
]) ?>

</div>