<?php
namespace test\controllers;

class IndexController extends \ifw\core\Controller
{
    public function actionIndex()
    {
        return $this->render('test.php');
    }
}
