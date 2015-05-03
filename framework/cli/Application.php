<?php

namespace ifw\cli;

class Application extends \ifw\core\Application
{
    public $controllerNamespace = 'clis';

    public function componentList()
    {
        return [
            'db' => '\\ifw\\components\\Db',
            'request' => '\\ifw\\cli\\Request',
        ];
    }

    public function run()
    {
        $route = $this->request->getParam(1);

        $routes = explode('/', $route);

        if (count($routes) !== 3) {
            throw new \Exception('the first cli parameter must be a route trailed by slashes. e.g module/clicontroller/action');
        }

        return $this->runRoute($routes[0], $routes[1], $routes[2]);
    }
}
