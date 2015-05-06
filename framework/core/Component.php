<?php

namespace ifw\core;

abstract class Component extends \ifw\core\Object
{
    private $_events = [];

    private $_behaviors = [];

    public function init()
    {
        parent::init();
        $this->attachBehaviors();
    }

    public function __clone()
    {
        $this->_events = [];
        $this->_behaviors = [];
    }

    public function __call($name, $arguments)
    {
        foreach ($this->_behaviors as $behavior) {
            if ($behavior->hasMethod($name)) {
                return call_user_func_array([$behavior, $name], $arguments);
            }
        }

        throw new \Exception("the requested method $name does not exists in this class or attached behavior.");
    }

    // behavior based methods

    /**
     * behavior array returns to to attache behaviors:
     * ```php
     * return [
     *     '\\example\\behavior'
     * ]
     * ```.
     *
     * you can also provided class with configurable propertys:
     * ```php
     * return [
     *     ['class' => '\\example\\behavior', 'prop1' => 'valueForProp1']
     * ];
     * ```
     *
     *
     * @return array:
     */
    public function behaviors()
    {
        return [];
    }

    public function attachBehaviors()
    {
        foreach ($this->behaviors() as $class) {
            $this->_behaviors[] = \ifw::createObject($class, ['context' => $this]);
        }
    }

    // event based methods

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

        if (is_array($handler) && isset($handler[0]) && is_object($handler[0]) && isset($handler[1]) && is_string($handler[1])) {
            return call_user_func_array([$handler[0], $handler[1]], []);
        }

        if (is_string($handler)) {
            $object = \ifw::createObject($handler);

            if ($object instanceof \ifw\core\Event) {
                $object->setContext($context);

                return $object->handler();
            }

            return $object->handler($context);
        }

        throw new \Exception('The registered event handler is not valid.');
    }
}
