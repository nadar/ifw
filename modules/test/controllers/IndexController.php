<?php
namespace test\controllers;

class IndexController extends \ifw\core\Controller
{
    public function actionIndex()
    {
        return \ifw::$app->request->get('test', false);
    }
}
