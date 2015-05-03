<?php

namespace ifw\web;

use Ifw;
use ifw\helpers\FileHelper;

abstract class Asset extends \ifw\core\Object
{
    public $sourceFolder = null;

    public $css = [];

    public $js = [];

    protected $view = null;

    public function init()
    {
        if ($this->sourceFolder === null) {
            throw new \ifw\core\Exception("The asset class propertys sourceFolder can't be null.");
        }

        $this->sourceFolder = Ifw::$app->getAlias($this->sourceFolder);

        $this->copy();
    }

    public function getCss()
    {
        $data = [];
        foreach ($this->css as $file) {
            $data[] = $this->getBaseDir().'/'.$file;
        }

        return $data;
    }

    public function getJs()
    {
        $data = [];
        foreach ($this->js as $file) {
            $data[] = $this->getBaseDir().'/'.$file;
        }

        return $data;
    }

    public function getBaseDir()
    {
        return 'cache/'.$this->getSourceFolderHash();
    }

    public function copy()
    {
        // create cache folder
        $cacheFolder = Ifw::$app->getAlias('@cache').DIRECTORY_SEPARATOR.$this->getSourceFolderHash();
        if (!file_exists($cacheFolder)) {
            mkdir($cacheFolder, 0775);
        }

        FileHelper::copyr($this->sourceFolder, $cacheFolder);
    }

    public function getSourceFolderHash()
    {
        return sprintf('%s', hash('crc32b', $this->sourceFolder));
    }

    public static function register(\ifw\web\View $view)
    {
        return $view->registerAsset(get_called_class());
    }
}
