<?php
namespace ifw\core;

class Application extends \ifw\core\Component
{
    private $_components = [];

    public $di = null;

    public $modules = [];

    public $defaultModule = null;

    public function init()
    {
        $this->parsePropertys([
            'di' => new \ifw\core\DiContainer(),
        ]);

        $this->addComponent('request', '\\ifw\\components\\Request');

        $this->parseModules();
    }

    public function __get($key)
    {
        if ($this->has($key)) {
            return $this->get($key);
        }

        parent::__get($key);
    }

    public function parseModules()
    {
        foreach ($this->modules as $id => $config) {
            $className = $config['class'];
            unset($config['class']);
            $this->di->add('modules.'.$id, $className, $config);
        }
    }

    public function getModule($id)
    {
        return ($this->hasModule($id)) ? $this->di->get('modules.'.$id) : false;
    }

    public function hasModule($id)
    {
        return array_key_exists($id, $this->modules);
    }

    public function addComponent($id, $class, array $params = [])
    {
        $this->di->add('components.'.$id, $class, $params);
        $this->_components[] = $id;
    }

    public function hasComponent($id)
    {
        return in_array($id, $this->_components);
    }

    public function getComponent($id)
    {
        return ($this->hasComponent($id)) ? $this->di->get('components.'.$id) : false;
    }

    public function run()
    {
        return $this->runRoute('test', 'index', 'index');
    }

    public function runRoute($module, $controller, $action)
    {
        return $this->getModule($module)->runController($controller)->runAction($action);
    }
}
