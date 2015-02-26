<?php
namespace ifw\core;

class Controller extends \ifw\core\Object
{
    public $id = null;
    
    public $module = null;

    public $layout = false;
    
    public function getView()
    {
        return \ifw::$app->view;
    }
    
    public function getViewPath()
    {
        return implode(DIRECTORY_SEPARATOR, ['@views', $this->module->id, $this->id]);
    }
    
    public function getLayoutPath()
    {
        return implode(DIRECTORY_SEPARATOR, ['@views', 'layouts']);
    }
    
    public function render($viewFile, array $params = [])
    {
        if (!$this->layout) {
            return $this->getView()->render($viewFile, $params, $this);
        }
        
        return $this->getView()->renderLayout($this->layout, $viewFile, $params, $this);
    }
    
    public function renderPartial($viewFile, array $params = [])
    {
        return $this->getView()->render($viewFile, $params, $this);
    }
    
    public function runAction($action)
    {
        $actionName = 'action'.ucfirst($action);
        if (!$this->hasMethod($actionName)) {
            throw new \Exception("The requested action $actionName does not exists.");
        }

        return $this->$actionName();
    }
}
