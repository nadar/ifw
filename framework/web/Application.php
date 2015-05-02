<?php

namespace ifw\web;

class Application extends \ifw\core\Application
{
    public $controllerNamespace = 'controllers';

    public function componentList()
    {
        return [
            'db' => '\\ifw\\components\\Db',
            'routing' => '\\ifw\\web\\Routing',
            'request' => '\\ifw\\web\\Request',
            'response' => '\\ifw\\web\\Response',
            'session' => '\\ifw\\web\\Session',
            'view' => '\\ifw\\web\\View',
        ];
    }
    
    public function run()
    {
        $route = $this->routing->getRouting($this, $this->request);
        return $this->runRoute($route[0], $route[1], $route[2]);
    }
}