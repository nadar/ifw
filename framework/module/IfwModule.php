<?php

namespace ifw\module;

class IfwModule extends \ifw\core\Module
{
    public static function bootstrap($app)
    {
        $app->addControllerMap('migration', ['module' => 'ifw', 'controller' => 'migration']);
    }
}