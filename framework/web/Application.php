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
            'user' => '\\ifw\\web\\User',
        ];
    }

    public function run()
    {
        $route = $this->routing->getRouting($this, $this->request);
        $response = $this->runRoute(implode("/", $route));
        $this->response->getHeader();
        return $response;
    }
}
