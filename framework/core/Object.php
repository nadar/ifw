<?php

namespace ifw\core;

abstract class Object
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
            throw new \Exception("The property '$key' does not exists inside of the class '".$this->getClass()."'");
        }

        $this->$key = $value;
    }

    public function __get($key)
    {
        $getter = 'get' . ucfirst($key);
        if ($this->hasMethod($getter)) {
            return $this->$getter();
        }
        if (!$this->hasProperty($key)) {
            throw new \Exception("The requested property '$key' does not exists in the class '".$this->getClass().'"');
        }

        return $this->$key;
    }

    public function getClass()
    {
        return get_class($this);
    }

    public static function className()
    {
        return get_called_class();
    }

    public function getClassNamespace()
    {
        return implode('\\', array_slice(explode('\\', $this->getClass()), 0, -1));
    }

    public function getPropertys()
    {
        return get_class_vars($this->getClass());
    }

    public function parsePropertys(array $propertys)
    {
        foreach ($propertys as $key => $value) {
            $this->$key = $value;
        }
    }

    public function hasProperty($key)
    {
        return property_exists($this->getClass(), $key);
    }

    public function hasMethod($methodName)
    {
        return method_exists($this, $methodName);
    }
    
    public function getBasePath()
    {
        $class = new \ReflectionClass($this);
        return dirname($class->getFileName());
    }

    public function getMethodArguments($methodName)
    {
        $reflection = new \ReflectionMethod($this, $methodName);
        $params = [];
        foreach ($reflection->getParameters() as $arg) {
            $params[] = \ifw\helpers\ArrayHelper::toObject([
                'name' => $arg->getName(),
                'isArray' => $arg->isArray(),
                'isOptional' => $arg->isOptional(),
                'defaultValue' => ($arg->isOptional()) ? $arg->getDefaultValue() : null,
            ]);
        }

        return $params;
    }
}
