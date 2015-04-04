<?php
namespace test\actions;

use Ifw;

class IndexAction extends \ifw\core\Action
{
    public $arg1 = null;
    
    public function run()
    {
        $model = new \test\models\Abc();
        $model->setAttribute('var1', 'ja');
        $model->validate();
        
    }
}
