<?php
/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 17.06.2016
 * Time: 16:16
 */
use yii\bootstrap\Html;

?>
<div class="ref-create">


    <?= $this->render('_form', [
        'model' => $referal_url,
        'user_id' => $user_id
    ]) ?>

</div>