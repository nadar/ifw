<?php

namespace ifw\cli;

class Request extends \ifw\components\Request
{
    private $_params = [];

    public function init()
    {
        $args = $_SERVER['argv'];
        unset($args[0]);
        $this->_params = $args;
    }

    public function getParam($key)
    {
        return $this->_params[$key];
    }

    public function getParams()
    {
        return $this->_params;
    }
}
