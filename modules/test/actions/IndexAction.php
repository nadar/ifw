<?php
namespace test\actions;

use Ifw;

class IndexAction extends \ifw\core\Action
{
    public $arg1 = null;
    
    public function run()
    {
        return 'hello world! ' . $this->arg1;
    }
}
