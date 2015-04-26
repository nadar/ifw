<?php

namespace ifw\web;

class Application extends \ifw\core\Application
{
    public $controllerNamespace = 'controllers';
    
    public function componentList()
    {
        return [
            'db' => '\\ifw\\components\\Db',
            'routing' => '\\ifw\\components\\Routing',
            'request' => '\\ifw\\components\\Request',
            'session' => '\\ifw\\components\\Session',
            'view' => '\\ifw\\components\\View',
        ];
    }
    
    public function runRoute($module, $controller, $action)
    {
        return $this->getModule($module)->runController($controller, $this->controllerNamespace)->runAction($action);
    }
    
    public function run()
    {
        $route = $this->routing->getRouting($this, $this->request);
        return $this->runRoute($route[0], $route[1], $route[2]);
    }
}