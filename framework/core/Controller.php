<?php
namespace ifw\core;

class Controller extends \ifw\core\Component
{
    public $id = null;
    
    public $module = null;

    public $layout = false;
    
    public $actions = [];
    
    const EVENT_BEFORE_ACTION = 'EVENT_BEFORE_ACTION';
    
    const EVENT_AFTER_ACTION = 'EVENT_AFTER_ACTION';
    
    public function getView()
    {
        return \ifw::$app->view;
    }
    
    public function getActions()
    {
        return $this->actions;
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
        $this->dispatchEvent(self::EVENT_BEFORE_ACTION);
        $actions = $this->getActions();
        
        if (array_key_exists($action, $actions)) {
            
            $actionProperty = $actions[$action];
            $actionParams = [];
            if (is_array($actionProperty)) {
                // @todo check if class isset else throw exception
                $className = $actionProperty['class'];
                unset($actionProperty['class']);
                $actionParams = $actionProperty;
            } else {
                $className = $actionProperty;
            }
            
            $actionParams['controller'] = $this;
            $actionParams['id'] = $action;
            
            $obj = new $className($actionParams);
            if (!$obj instanceof \ifw\core\Action) {
                throw new \ifw\core\Exception("The requested action class must be an instance of ifw\core\Action.");
            }
            $response = $obj->run();
        } else {
            $actionName = 'action'.ucfirst($action);
            if (!$this->hasMethod($actionName)) {
                throw new \Exception("The requested action $actionName does not exists.");
            }
            $response = $this->$actionName();
        }
        $this->dispatchEvent(self::EVENT_AFTER_ACTION);
        return $response;
    }
}
