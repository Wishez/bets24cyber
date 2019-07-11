<?php 
use app\models\Currency;

$currency = Currency::find()->all();
?>

<div class="currency_list">
	<span class="currency" selected data-currency="1">[USD]</span>
	<?php foreach ($currency as $value) { ?>
    	<span class="currency" data-currency="<?= $value['course'] ?>">[<?= $value['name'] ?>]</span>
    <?php } ?>

</div>