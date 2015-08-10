<?php

namespace ifw\core;

use Ifw;

abstract class Controller extends \ifw\core\Component
{
    public $id = null;

    public $module = null;

    protected $response = null;

    const EVENT_BEFORE_ACTION = 'EVENT_BEFORE_ACTION';

    const EVENT_AFTER_ACTION = 'EVENT_AFTER_ACTION';

    /**
     * ```php
     * return [
     *     'index' => '\\example\\Action',
     *     'anotherIndex' => \app\ns\Action::className(),
     * ]
     * ```.
     *
     * you can also provided class with configurable propertys:
     * ```php
     * return [
     *     'index' => ['class' => '\\example\\action', 'prop1' => 'valueForProp1']
     * ];
     * ```
     *
     * @return array:
     */
    public function actions()
    {
        return [];
    }

    public function runAction($action)
    {
        Ifw::trace("run action '$action'");

        $request = Ifw::$app->request;
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
            // @todo should implement the request of data inside the specific controller (cli/web)
            if (!$request->isCli()) {
                $actionTypes = $this->actionTypes();
                $dataType = array_key_exists($action, $actionTypes) ? $actionTypes[$action] : $request::TYPE_GET;
                $data = $request->getData($dataType);
            } else {
                $data = $request->getParams();
            }
            foreach ($this->getMethodArguments($actionName) as $arg) {
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
