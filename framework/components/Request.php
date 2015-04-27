<?php

namespace ifw\components;

abstract class Request extends \ifw\core\Component
{
    public function isCli()
    {
        return (strtolower(php_sapi_name()) == "cli") ? true : false;
    }
}