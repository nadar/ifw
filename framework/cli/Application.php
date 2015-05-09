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
        return $this->runRoute($this->request->getParam(1));
    }
}
