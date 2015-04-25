<?php
namespace ifw\core;

class Application extends \ifw\core\Component
{
    public $components = [];

    public $di = null;

    public $modules = [];

    public $defaultModule = null;

    public $aliases = [];
    
    public $basePath = null;
    
    protected $loader = null;
    
    public function init()
    {
        if (empty($this->basePath)) {
            throw new \Exception("The base path directory must be set");
        }
        
        if (empty($this->defaultModule)) {
            throw new \Exception("Property defaultModule must be set.");
        }
        
        if (empty($this->modules)) {
            throw new \Exception("At least one modules need to be set.");
        }
        
        $this->setAlias('app', realpath($this->basePath)); // go back
        $this->setAlias('views', '@app/views');
        
        $this->loader = new Loader();
        $this->loader->addNamespace("app", $this->getAlias('@app'));
        $this->loader->register();
        
        $this->di = new DiContainer();

        $this->parseModules();
        $this->parseComponents();
        
        $this->addComponent('session', '\\ifw\\components\\Session');
        $this->addComponent('request', '\\ifw\\components\\Request');
        $this->addComponent('routing', '\\ifw\\components\\Routing');
        $this->addComponent('view', '\\ifw\\components\\View');
        $this->addComponent('db', '\\ifw\\components\\Db');
        
        $this->bootstrap();
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
            $this->di->append('modules.'.$id, $config['class'], array_merge($config, ['id' => $id]));
        }
    }

    public function bootstrap()
    {
        foreach ($this->modules as $config) {
            $className = $config['class'];
            $className::bootstrap($this);
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
            $this->addComponent($id, (isset($config['class'])) ? $config['class'] : null, $config);
        }
    }
    
    public function addComponent($id, $class = null, array $params = [])
    {
        $this->di->append('components.'.$id, $class, $params);
        
        if (!$this->hasComponent($id)) {
            $this->components[] = $id;
        }
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
