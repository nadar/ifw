<?php
namespace ifw\core;

class Controller extends \ifw\core\Object
{
    public $module = null;
    
    public function runAction($action)
    {
        $actionName = 'action' . ucfirst($action);
        if (!$this->hasMethod($actionName)) {
            throw new \Exception("The requested action $actionName does not exists.");
        }
        
        return $this->$actionName();
    }
}