<?php
namespace ifw\components;

class Request extends \ifw\core\Component
{
    const TYPE_GET = 'GET';
    
    const TYPE_POST = 'POST';
    
    private $_dataTypes = [self::TYPE_GET, self::TYPE_POST];
    
    private $_get = null;
    
    private $_post = null;
    
    private function ensureType($dataType)
    {
        $dataType = strtoupper($dataType);
        if (!in_array($dataType, $this->_dataTypes)) {
            throw new \ifw\core\Exception("The dataType $dataType does not exists.");
        }
        
        return $dataType;
    }
    
    public function get($varName, $defaultValue = null)
    {
        $data = $this->getData(self::TYPE_GET);
        if (!array_key_exists($varName, $data)) {
            return $defaultValue;
        }
        return $data[$varName];
    }
    
    public function post($varName, $defaultValue = null)
    {
        $data = $this->getData(self::TYPE_POST);
        if (!array_key_exists($varName, $data)) {
            return $defaultValue;
        }
        return $data[$varName];
    }
    
    public function getData($dataType)
    {
        $dataType = $this->ensureType($dataType);
        switch($dataType) {
            case self::TYPE_GET:
                return (is_null($this->_get)) ? $this->_get = $_GET : $this->_get;
                break;
            case self::TYPE_POST:
                return (is_null($this->_post)) ? $this->_post = $_POST : $this->_post;
                break;
            default:
                throw new \ifw\core\Exception("the dataType $dataType does not exists!");
                break;
        }
    }
}
