<?php

namespace ifw\web;

class Request extends \ifw\components\Request
{
    const TYPE_GET = 'GET';

    const TYPE_POST = 'POST';

    const TYPE_FILES = 'FILES';

    const TYPE_SERVER = 'SERVER';

    private $_dataTypes = [self::TYPE_GET, self::TYPE_POST, self::TYPE_FILES, self::TYPE_SERVER];

    private $_get = [];

    private $_post = [];

    private $_files = [];

    private $_server = [];

    public function init()
    {
        $this->_get = $_GET;
        $this->_post = $_POST;
        $this->_files = $_FILES;
        $this->_server = $_SERVER;
    }

    private function ensureType($dataType)
    {
        if (!in_array(strtoupper($dataType), $this->_dataTypes)) {
            throw new \ifw\core\Exception("The dataType $dataType does not exists.");
        }

        return $dataType;
    }

    private function exception($key)
    {
        return "You can not override an existing variable \"$key\" by customer variable setter.";
    }

    // get

    public function get($key = null, $defaultValue = null)
    {
        if ($key === null) {
            return $this->_get;
        }

        return ($this->hasGet($key)) ? $this->_get[$key] : $defaultValue;
    }

    public function setGet($key, $value)
    {
        if ($this->hasGet($key)) {
            throw new \ifw\core\Exception($this->exception($key));
        }

        $this->_get[$key] = $value;
    }

    public function hasGet($key)
    {
        return array_key_exists($key, $this->_get);
    }

    // post

    public function post($key = null, $defaultValue = null)
    {
        if ($key === null) {
            return $this->_post;
        }

        return (array_key_exists($key, $this->_post)) ? $this->_post[$key] : $defaultValue;
    }

    public function setPost($key, $value)
    {
        if ($this->hasPost($key)) {
            throw new \ifw\core\Exception($this->exception($key));
        }

        $this->_post[$key] = $value;
    }

    public function hasPost($key)
    {
        return array_key_exists($key, $this->_post);
    }

    // files

    public function files($key = null)
    {
        if ($key === null) {
            return $this->_files;
        }

        return (array_key_exists($key, $this->_files)) ? $this->_files[$key] : false;
    }

    // server

    public function server($key = null)
    {
        if ($key === null) {
            return $this->_server;
        }

        return (array_key_exists($key, $this->_server)) ? $this->_server[$key] : false;
    }

    // global methods

    public function setData($dataType, $key, $value)
    {
        $dataType = $this->ensureType($dataType);
        switch ($dataType) {
            case self::TYPE_GET:
                return $this->setGet($key, $value);
                break;
            case self::TYPE_POST:
                return $this->setPost($key, $value);
                break;
            default:
                throw new \ifw\core\Exception("the dataType $dataType does not exists!");
                break;
        }
    }

    public function getData($dataType)
    {
        $dataType = $this->ensureType($dataType);
        switch ($dataType) {
            case self::TYPE_GET:
                return $this->_get;
                break;
            case self::TYPE_POST:
                return $this->_post;
                break;
            case self::TYPE_FILES:
                return $this->_files;
                break;
            case self::TYPE_SERVER:
                return $this->_server;
                break;
            default:
                throw new \ifw\core\Exception("the dataType $dataType does not exists!");
                break;
        }
    }
}
