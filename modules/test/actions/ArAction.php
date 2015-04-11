<?php
namespace test\actions;

use Ifw;

class ArAction extends \ifw\core\Action
{
    public function run()
    {
        $activeModel = new \test\models\ActiveXyz();
        $data = $activeModel::find()->all();
        var_dump($data);
    }
}
