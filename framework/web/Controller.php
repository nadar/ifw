<?php

namespace ifw\web;

use Ifw;

abstract class Controller extends \ifw\core\Controller
{
    public $layout = false;

    public function getView()
    {
        return Ifw::$app->view;
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
}
