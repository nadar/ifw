<?php

namespace ifw\cli;

class Application extends \ifw\core\Application
{
    public $controllerNamespace = 'clis';
    
    public function init()
    {
        var_dump('asdf');
        exit;
    }
    /*
    public function init()
    {
        parent::init();
        
        $this->addComponent('session', '\\ifw\\components\\Session');
        $this->addComponent('view', '\\ifw\\components\\View');
    }
    
    public function componentList()
    {
        return [
            'db' => '\\ifw\\components\\Db',
            'routing' => '\\ifw\\cli\\Routing',
            'request' => '\\ifw\\components\\Request'
        ];
    }
    
    */
}