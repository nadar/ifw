<?php
namespace ifw\core;

abstract class Event extends \ifw\core\Component
{
    protected $context = null;
    
    public function setContext($context)
    {
        $this->context = $context;
    }
    
    abstract function handler();
}