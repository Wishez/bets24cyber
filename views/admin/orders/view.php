<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Orders */

$this->title = 'Ордер №'.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="orders-view">

<?php foreach ($model->logs as $key => $log): ?>
    <div class="row">
        <div class="col-md-10"><?=$log->text?></div>
        <div class="col-md-2"><?=date('d-m-Y H:i:s', $log->date)?></div>
    </div>
<?php endforeach ?>    
    

</div>
