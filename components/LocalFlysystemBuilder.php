<?php
namespace app\components;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local as Adapter;
use trntv\filekit\filesystem\FilesystemBuilderInterface;

class LocalFlysystemBuilder implements FilesystemBuilderInterface
{
    public $path;

    public function build()
    {
        $adapter = new Local(\Yii::getAlias($this->path));
        return new Filesystem($adapter);
    }
}