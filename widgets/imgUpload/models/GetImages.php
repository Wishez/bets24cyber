<?php

/**
 * Created by PhpStorm.
 * User: Vlad
 * Date: 13.02.2016
 * Time: 14:27
 */
namespace app\widgets\imgUpload\models;

use SplFileInfo;
use Yii;

class GetImages
{
    public function getImages($url, $path)
    {
        $files = \yii\helpers\FileHelper::findFiles(Yii::getAlias($path));
        $response = [];

//        var_dump($files);
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        foreach ($files as $file) {
            $file_info = new SplFileInfo($file);
            $ext = $file_info->getExtension();
//            var_dump($ext);
            $mime = finfo_file($finfo, $file);
//            var_dump($mime);
            if (preg_match("/[.](JPG)|(jpg)|(gif)|(GIF)|(png)|(PNG)$/iu",$ext) && ($mime == "image/jpeg" || $mime == "image/png" || $mime == "image/gif")) {
                $response[] = $url . "/" . $file_info->getFilename();
            }
        }
        finfo_close($finfo);
        return $response;
    }
}