<?php
namespace ifw\components;

class Request extends \ifw\core\Component
{
    private $_get = null;
    
    public function get($varName, $defaultValue = false)
    {
        if (is_null($this->_get)) {
            $this->_get = $_GET;
        }
        
        if (!array_key_exists($varName, $this->_get)) {
             return $defaultValue;
        }
        
        return $this->_get[$varName];
    }
}
