<?php
/**
 * Created by PhpStorm.
 * User: lexam85
 * Date: 20.08.15
 * Time: 10:27
 */

namespace app\widgets\imgUpload\models;

use yii\base\Model;
use yii\helpers\Url;
use yii\web\UploadedFile;

class ImgUploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    public function rules()
    {
        return [
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, gif', 'maxSize'=>1024*1024*5],
//            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'txt, doc, docx, pdf, rtf', 'maxSize'=>5*1024*1024],
//            [['video'], 'file', 'skipOnEmpty' => true, 'extensions' => 'mp4, webm', 'maxSize'=>50*1024*1024],
        ];
    }
    public function attributeLabels()
    {
        return [
            'imageFile' => 'Добавить/Заменить фото',

        ];
    }

    public function uploadLogo()
    {
        if ($this->validate()&& $this->imageFile) {
            $this->imageFile->saveAs('uploads/Logo.' . $this->imageFile->extension);
            return true;
        } else {
            return false;
        }
    }


}