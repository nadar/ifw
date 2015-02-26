<?php
namespace ifw\core;

class Application extends \ifw\core\Component
{
    public $components = [];

    public $di = null;

    public $modules = [];

    public $defaultModule = null;

    public $aliases = [];
    
    public function init()
    {
        $this->setAlias('app', realpath(getcwd())); // go back
        $this->setAlias('views', '@app/views');
        
        $this->parsePropertys([
            'di' => new \ifw\core\DiContainer(),
        ]);

        $this->parseModules();
        $this->parseComponents();
        
        $this->addComponent('request', '\\ifw\\components\\Request');
        $this->addComponent('routing', '\\ifw\\components\\Routing');
        $this->addComponent('view', '\\ifw\\components\\View');
        
        if (empty($this->defaultModule)) {
            throw new \Exception("Property defaultModule must be set.");
        }
        
        if (empty($this->modules)) {
            throw new \Exception("At least one modules need to be set.");
        }
    }

    public function __get($key)
    {
        if ($this->hasComponent($key)) {
            return $this->getComponent($key);
        }

        parent::__get($key);
    }

    public function parseModules()
    {
        foreach ($this->modules as $id => $config) {
            if (!isset($config['class'])) {
                throw new \Exception('the module does not have class property');
            }
            $className = $config['class'];
            unset($config['class']);
            $this->di->add('modules.'.$id, $className, array_merge($config, ['id' => $id]));
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

    public function parseComponents()
    {
        foreach ($this->components as $id => $config) {
            if (!isset($config['class'])) {
                throw new \Exception('the component does not have class property');
            }
            $className = $config['class'];
            unset($config['class']);
            $this->addComponent($id, $className, $config);
        }
    }
    
    public function addComponent($id, $class, array $params = [])
    {
        $this->di->add('components.'.$id, $class, $params);
        $this->components[] = $id;
    }

    public function hasComponent($id)
    {
        return in_array($id, $this->components);
    }

    public function getComponent($id)
    {
        return ($this->hasComponent($id)) ? $this->di->get('components.'.$id) : false;
    }

    public function runRoute($module, $controller, $action)
    {
        return $this->getModule($module)->runController($controller)->runAction($action);
    }
    
    public function run()
    {
        $route = $this->routing->getRouting($this, $this->request);
        return $this->runRoute($route[0], $route[1], $route[2]);
    }
    
    public function setAlias($alias, $path)
    {
        $this->aliases[$alias] = $this->getAlias($path);
    }
    
    public function getAlias($find)
    {
        $_search = [];
        $_replace = [];
        foreach ($this->aliases as $alias => $path) {
            $_search[] = "@" .$alias;
            $_replace[] = $path;
        }
        return str_replace($_search, $_replace, $find);
    }
}
