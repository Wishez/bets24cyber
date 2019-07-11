<?php
/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 11.03.2016
 * Time: 15:06
 */

use yii\bootstrap\Html;
use yii\helpers\Url;

foreach ($model as $banner) { ?>
    <div class="game-img">
        <a href="<?= $banner->link ?>">
            <img src="<?= $banner->img ?>" alt="<?= $banner->alt_text ?>">
        </a>

    </div>
<?php } ?>