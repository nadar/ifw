<?php
namespace ifw\core;

abstract class Application extends \ifw\core\Component
{
    public $components = [];

    public $di = null;

    public $modules = [];

    public $defaultModule = null;

    public $aliases = [];
    
    public $basePath = null;
    
    public $controllerNamespace = null;
    
    protected $loader = null;
    
    abstract public function run();
    
    abstract public function componentList();
    
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
        
        foreach($this->componentList() as $name => $class) {
            $this->addComponent($name, $class);
        }
        
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
