<?php
namespace test;

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

    public function getRules()
    {
        return [
            ['rule' => 'test/index/sub', 'pattern' => 'foobar']
        ];
    }
}
