<?php
namespace ifw\core;

use Ifw;

abstract class Controller extends \ifw\core\Component
{
    public $id = null;
    
    public $module = null;

    public $layout = false;
    
    protected $response = null;
    
    const EVENT_BEFORE_ACTION = 'EVENT_BEFORE_ACTION';
    
    const EVENT_AFTER_ACTION = 'EVENT_AFTER_ACTION';
    
    public function getView()
    {
        return Ifw::$app->view;
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
    
    /**
     * The data type which should be used to verify the action arguments. The data type must be
     * defined for each method, otherwhise the default data type is GET.
     * 
     * ```php
     * return [
     *     'index' => 'GET',
     *     'someOtherMethod' => 'POST',
     *     'someOtherMethod' => \ifw\components\Request::TYPE_GET,
     *     'someOtherMethod' => ifw::$app->request::TYPE_GET,
     * ];
     * ```
     * 
     * @return array:
     */
    public function actionTypes()
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
            return $this->renderPartial($viewFile, $params);
        }
        
        return $this->getView()->renderLayout($this->layout, $viewFile, $params, $this);
    }
    
    public function renderPartial($viewFile, array $params = [])
    {
        return $this->getView()->render($viewFile, $params, $this);
    }
    
    public function runAction($action)
    {
        $request = \ifw::$app->request;
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
            $params = [];
            $actionTypes = $this->actionTypes();
            $dataType = array_key_exists($action, $actionTypes) ? $actionTypes[$action] : $request::TYPE_GET;
            $data = $request->getData($dataType);
            foreach($this->getMethodArguments($actionName) as $arg) {
                if (array_key_exists($arg->name, $data)) {
                    $value = $data[$arg->name];
                    if ($arg->isArray) {
                        if (!is_array($value)) {
                            throw new Exception("The paremeter '{$arg->name}' for action '$actionName' must be an array!");
                        }   
                    }
                } else {
                    if (!$arg->isOptional) {
                        throw new Exception("The action '$actionName' requires the $dataType parameter '{$arg->name}'.");
                    }
                    $value = $arg->defaultValue;
                }
                $params[] = $value;
            }
            $this->response = call_user_func_array([$this, $actionName], $params);
        }
        $this->dispatchEvent(self::EVENT_AFTER_ACTION);
        return $this->response;
    }
}
