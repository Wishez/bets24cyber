<?php
/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 12.02.2016
 * Time: 23:18
 */
use yii\helpers\Url;

//echo Url::to($url);
//echo $path;
//$path = \yii\helpers\BaseUrl::to("@app/web/uploads/images");

?>
<div class="img-upl">

    <?php if (!$isAvatar) { ?>
        <img src='<?= $img ?>' class='upload-image'/>
        <input type="hidden" name="<?= $inputName ?>" value="<?= $img ?>">
        <!-- Modal -->
        <div class="modal fade" id="Modal<?= $widgetId ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Выбор изображения</h4>
                    </div>
                    <div class="modal-body">

                    </div>
                    <div class="modal-footer">
                        <label for='image-upload' class="btn btn-primary">Загрузить</label>
                        <input type='file' id='image-upload' name='file' accept="image/*">

                        <button type=" button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <img src='<?= $img ?>' width="50" height="50" class='upload-image avatar'/>
        <input type="hidden" name="<?= $inputName ?>" value="<?= $img ?>">
        <input style="display: none;" type='file' id='image-upload' name='file' accept="image/*">
    <?php } ?>
</div>


