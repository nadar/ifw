<?php
namespace ifw\core;

class Component extends \ifw\core\Object
{
    private $_events = [];
    
    public function on($eventName, $handler)
    {
        $this->_events[$eventName][] = $handler;   
    }
    
    public function dispatchEvent($eventName, $context = null)
    {
        if (is_null($context)) {
            $context = $this;
        }
        
        if (!$this->hasEventHandlers($eventName)) {
            return true;
        }
        
        foreach ($this->_events[$eventName] as $handler) {
            if ($this->callHandler($handler, $context) !== true) {
                return false;
            }
        }
        
        return true;
    }
    
    public function hasEventHandlers($eventName)
    {
        return array_key_exists($eventName, $this->_events);
    }
    
    private function callHandler($handler, $context)
    {
        
        if (is_object($handler) && !($handler instanceof \Closure)) {
            return $handler->handler($context);
        }
        
        if (is_callable($handler)) {
            return $handler($context);
        }
        
        if (is_string($handler)) {
            $object = new $class();
            
            if ($object instanceof \ifw\core\Event) {
                $object->setContext($context);
                return $object->handler();
            }
            
            return $object->handler($context);
        }
        
        throw new \Exception("The registered event handler is not valid.");
    }
}