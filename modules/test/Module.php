<?php
namespace app\modules\test;

class Module extends \ifw\core\Module
{
    /*
    public function init()
    {
        $this->on(self::EVENT_BEFORE_CONTROLLER, function ($context) {
            echo "EVENT_BEFORE_CONTROLLER_TEST_HANDLER";

            return true;
        });
    }
    */

    public static function bootstrap($app)
    {
        if (!$app->request->isCli()) {
            $app->addComponent('storage', '\\app\\modules\\test\\components\\Storage');
            $app->routing->addrule('test/index/<action>', 'das-modul/<action:\w+>');
            $app->routing->addRule('test/index/sub', 'shorturl/<id:\d+>');
            $app->routing->addRule('test/index/foo', 'foo/<id:\d+>/bla');
        }
    }
}
