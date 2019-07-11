<?php
namespace app\widgets\assets;
use yii\web\AssetBundle;

/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 12.02.2016
 * Time: 0:04
 */

class AssetImageUpload extends AssetBundle
{
    public $sourcePath = '@app/widgets/assets/srcImageUpload';
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