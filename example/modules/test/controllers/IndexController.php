<?php
namespace app\modules\test\controllers;

use Ifw;

class IndexController extends \ifw\web\Controller
{
    public $layout = 'main.php';
    
    /*
    public function init()
    {
        parent::init();
        $this->on(self::EVENT_AFTER_ACTION, function($context) {
            $context->response = json_encode($context->response); 
        });
    }
    */
    
    /*
    public function behaviors()
    {
        return [
            \ifw\behaviors\Json::className()
        ];
    }
    */
    
    public function actions()
    {
        return [
            'index' => \app\modules\test\actions\ArAction::className()
        ];
    }
    
    public function actionUser()
    {
        $user = \app\modules\test\models\User::find()->where(['id' => 1])->one();
        var_dump($user);
        exit;
        $identity = \ifw::$app->user->login($user);
        var_dump($user);
        exit;
    }
    
    public function actionTypes()
    {
        return [
            'sub' => 'POST'  
        ];
    }
    
    public function actionView()
    {
        return $this->render('view.php');
    }
    
    /*
    public function actionIndex()
    {
        $data = (new \ifw\db\Query())->select(['id', 'name', 'street' => 'Strasse'])->from('xyz')->where(['id' => 1])->query();
        
        var_dump($data->fetchAll());
        
        return $this->render('test.php', ['id' => $this->id]);
    }
    */
    public function actionSub($foo, $bar = false)
    {
        var_dump($foo, $bar);
        exit;
        return $this->render('sub.php', ['id' => $this->id]);
    }
}
