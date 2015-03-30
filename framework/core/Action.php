<?php
namespace ifw\core;

abstract class Action extends \ifw\core\Component
{
    public $controller;
    
    public $id;
    
    abstract public function run();
}