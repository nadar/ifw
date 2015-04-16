<?php
namespace ifw\core;

class Controller extends \ifw\core\Component
{
    public $id = null;
    
    public $module = null;

    public $layout = false;
    
    protected $response = null;
    
    const EVENT_BEFORE_ACTION = 'EVENT_BEFORE_ACTION';
    
    const EVENT_AFTER_ACTION = 'EVENT_AFTER_ACTION';
    
    public function getView()
    {
        return \ifw::$app->view;
    }
    
    /**
     * 
     * ```php
     * return [
     *     'index' => '\\example\\Action',
     *     'anotherIndex' => \app\ns\Action::className(),
     * ]
     * ```
     * 
     * you can also provided class with configurable propertys:
     * ```php
     * return [
     *     'index' => ['class' => '\\example\\action', 'prop1' => 'valueForProp1']
     * ];
     * ```
     * @return array:
     */
    public function actions()
    {
        return [];
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
        $actions = $this->actions();
        
        if (array_key_exists($action, $actions)) {
            $obj = \ifw::createObject($actions[$action], ['controller' => $this, 'id' => $action]);
            
            if (!$obj instanceof \ifw\core\Action) {
                throw new \ifw\core\Exception("The requested action class must be an instance of ifw\core\Action.");
            }
            $this->response = $obj->run();
        } else {
            $actionName = 'action'.ucfirst($action);
            if (!$this->hasMethod($actionName)) {
                throw new \Exception("The requested action $actionName does not exists.");
            }
            $this->response = $this->$actionName();
        }
        $this->dispatchEvent(self::EVENT_AFTER_ACTION);
        return $this->response;
    }
}
