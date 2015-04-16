<?php
namespace app\modules\test\controllers;

use Ifw;

class IndexController extends \ifw\core\Controller
{
    public $layout = 'main.php';
    
    public function getActions()
    {
        return [
            'index' => [
                'class' => 'app\modules\test\actions\ArAction',
                // 'class' => 'test\actions\IndexAction',
            ]
        ];
    }
    
    /*
    public function actionIndex()
    {
        $data = (new \ifw\db\Query())->select(['id', 'name', 'street' => 'Strasse'])->from('xyz')->where(['id' => 1])->query();
        
        var_dump($data->fetchAll());
        
        return $this->render('test.php', ['id' => $this->id]);
    }
    */
    public function actionSub()
    {
        return $this->render('sub.php', ['id' => $this->id]);
    }
}
