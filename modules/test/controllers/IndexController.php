<?php
namespace test\controllers;

class IndexController extends \ifw\core\Controller
{
    public $layout = 'main.php';
    
    public function actionIndex()
    {
        return $this->render('test.php', ['id' => $this->id]);
    }
}
