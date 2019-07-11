<?php

namespace app\widgets\imgUpload\actions;

use Gregwar\Image\Image;
use Imagine\Image\Box;
use vova07\imperavi\Widget;
use yii\base\Action;
use yii\base\DynamicModel;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;
//use yii\imagine\Image;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use Imagine\Gd;
use Imagine\Image\BoxInterface;
use Yii;

/**
 * Class UploadAction
 * @package vova07\imperavi\actions
 *
 * UploadAction for images and files.
 *
 * Usage:
 *
 * ```php
 * public function actions()
 * {
 *     return [
 *         'upload-image' => [
 *             'class' => 'vova07\imperavi\actions\UploadAction',
 *             'url' => 'http://my-site.com/statics/',
 *             'path' => '/var/www/my-site.com/web/statics',
 *             'validatorOptions' => [
 *                 'maxWidth' => 1000,
 *                 'maxHeight' => 1000
 *             ]
 *         ],
 *         'file-upload' => [
 *             'class' => 'vova07\imperavi\actions\UploadAction',
 *             'url' => 'http://my-site.com/statics/',
 *             'path' => '/var/www/my-site.com/web/statics',
 *             'uploadOnlyImage' => false,
 *             'validatorOptions' => [
 *                 'maxSize' => 40000
 *             ]
 *         ]
 *     ];
 * }
 * ```
 *
 * @author Vasile Crudu <bazillio07@yandex.ru>
 *
 * @link https://github.com/vova07
 */
class UploadAction extends Action
{

    public $avatar = false;
    /**
     * @var string Path to directory where files will be uploaded
     */
    public $path;

    /**
     * @var string URL path to directory where files will be uploaded
     */
    public $url;

    /**
     * @var string Validator name
     */
    public $uploadOnlyImage = true;

    /**
     * @var string Variable's name that Imperavi Redactor sent upon image/file upload.
     */
    public $uploadParam = 'file';

    /**
     * @var boolean If `true` unique filename will be generated automatically
     */
    public $unique = true;

    /**
     * @var array Model validator options
     */
    public $validatorOptions = [];

    /**
     * @var string Model validator name
     */
    private $_validator = 'image';

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->url === null) {
            throw new InvalidConfigException('The "url" attribute must be set.');
        } else {
            $this->url = rtrim($this->url, '/') . '/';
        }
        if ($this->path === null) {
            throw new InvalidConfigException('The "path" attribute must be set.');
        } else {
            $this->path = rtrim(Yii::getAlias($this->path), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

            if (!FileHelper::createDirectory($this->path)) {
                throw new InvalidCallException("Directory specified in 'path' attribute doesn't exist or cannot be created.");
            }
        }

    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (Yii::$app->request->isPost) {
            $file = UploadedFile::getInstanceByName('image');
            $model = new DynamicModel(compact('file'));
            $model->addRule('file', 'image', $this->validatorOptions)->validate();
            if ($model->hasErrors()) {
                $result = [
                    'error' => $model->getFirstError('file')
                ];
            } else {
                $_imagename = $model->file->name;
//                $_imagename = iconv("UTF-8", "windows-1251", $model->file->name);
                if ($this->avatar === true && $model->file->extension) {
                    $_imagename = uniqid() . '.' . $model->file->extension;
                }

                if ($this->avatar === true) {
                    @mkdir($this->path . Yii::$app->user->identity->email);
                    $_imagename = Yii::$app->user->identity->avatar ? basename(Yii::$app->user->identity->avatar) : $_imagename;
                    if ($model->file->saveAs($this->path . Yii::$app->user->identity->email . "/" . $_imagename)) {
                        Image::open($this->path . Yii::$app->user->identity->email . "/" . $_imagename)->cropResize(50, 50)->save($this->path . Yii::$app->user->identity->email . "/" . $_imagename);
                        $_imagename = Yii::$app->user->identity->email . "/" . $_imagename;
                        $result = ['filelink' => $this->url . $_imagename];
                    } else {
                        $result = [
                            'error' => 'ERROR_CAN_NOT_UPLOAD_FILE'
                        ];
                    }
                } else {
                    if ($model->file->saveAs($this->path . $_imagename)) {

                        $result = ['filelink' => $this->url . $_imagename];
                        if ($this->uploadOnlyImage !== true) {
                            $result['filename'] = $_imagename;
                        }
                    } else {
                        $result = [
                            'error' => 'ERROR_CAN_NOT_UPLOAD_FILE'
                        ];
                    }
                }
            }

            Yii::$app->response->format = Response::FORMAT_JSON;

            return $result;
        }


    }
}
