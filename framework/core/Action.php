<?php

namespace ifw\core;

abstract class Action extends \ifw\core\Object
{
    public $controller;

    public $id;

    public function __call($name, $arguments)
    {
        return $this->controller->__call($name, $arguments);
    }

    abstract public function run();
}
