<?php
namespace app\widgets\imgUpload\assets;

use yii\web\AssetBundle;

/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 12.02.2016
 * Time: 0:04
 */

class AssetImageUpload extends AssetBundle
{
    public $sourcePath = '@app/widgets/imgUpload/assets/';
    public $css = [
        'imageUpload.css'
    ];
    public $js = [
        'imageUpload.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
    public function registerAssetFiles($view)
    {
        parent::registerAssetFiles($view);
    }


}