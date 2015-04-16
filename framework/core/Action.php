<?php
namespace ifw\core;

abstract class Action extends \ifw\core\Object
{
    public $controller;
    
    public $id;
    
    abstract public function run();
}