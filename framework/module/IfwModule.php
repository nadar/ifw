<?php

namespace ifw\module;

class IfwModule extends \ifw\core\Module
{
    public static function bootstrap($app)
    {
        $app->addControllerMap('help', ['module' => 'ifw', 'controller' => 'help']);
        $app->addControllerMap('migration', ['module' => 'ifw', 'controller' => 'migration']);
    }
}