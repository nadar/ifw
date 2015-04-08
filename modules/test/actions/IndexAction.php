<?php
namespace test\actions;

use Ifw;

class IndexAction extends \ifw\core\Action
{
    public $arg1 = null;
    
    public function run()
    {
        $model = new \test\models\Abc();
        $model->setAttribute('title', 'Herr');
        $model->name = 'Radan';
        $v = $model->validate();
        var_dump($model->getErrors());
        
    }
}
