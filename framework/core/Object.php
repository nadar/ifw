<?php
namespace ifw\core;

class Object
{
    public function __construct(array $params = [])
    {
        $this->parsePropertys($params);
        $this->init();
    }
    
    public function init()
    {
        // override
    }
    
    public function __set($key, $value)
    {
        if (!$this->hasProperty($key)) {
            throw new \Exception("The property $key does not exists");
        }
        
        $this->$key = $value;
    }
    
    public function __get($key)
    {
        return $this->$key;
    }
    
    public function getClass()
    {
        return get_class($this);
    }
    
    public function parsePropertys(array $propertys)
    {
        foreach($propertys as $key => $value) {
            $this->$key = $value;
        }
    }
    
    public function hasProperty($key)
    {
        return property_exists($this->getClass(), $key);
    }
    
    public function getClassNamespace()
    {
        return implode("\\", array_slice(explode('\\', $this->getClass()), 0, -1));
    }
    
    public function hasMethod($methodName)
    {
        return method_exists($this, $methodName);
    }
}