<?php
namespace ifw\core;

class Module extends \ifw\core\Component
{
    const EVENT_BEFORE_CONTROLLER = 'EVENT_BEFORE_CONTROLLER';
    
    public function runController($controllerName)
    {
        $className = $this->getClassNamespace() . '\\controllers\\' . $controllerName . 'Controller';
        $this->dispatchEvent(self::EVENT_BEFORE_CONTROLLER);
        return new $className(['module' => $this]);
    }
}