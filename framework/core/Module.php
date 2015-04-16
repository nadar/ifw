<?php
namespace ifw\core;

class Module extends \ifw\core\Component
{
    const EVENT_BEFORE_CONTROLLER = 'EVENT_BEFORE_CONTROLLER';
    
    const EVENT_AFTER_CONTROLLER = 'EVENT_AFTER_CONTROLLER';

    public $id = null;

    protected $response = null;
    
    public function runController($controllerName)
    {
        $className = $this->getClassNamespace().'\\controllers\\'.$controllerName.'Controller';
        $this->dispatchEvent(self::EVENT_BEFORE_CONTROLLER);
        $this->response = \ifw::createObject($className, ['module' => $this, 'id' => $controllerName]);
        $this->dispatchEvent(self::EVENT_AFTER_CONTROLLER);
        return $this->response;
    }
    
    public static function bootstrap($app)
    {
        // override
    }
}
