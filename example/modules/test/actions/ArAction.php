<?php
namespace app\modules\test\actions;

use Ifw;

class ArAction extends \ifw\core\Action
{
    public function run()
    {
        $url = \ifw\helpers\UrlHelper::to('test/index/sub', ['id' => 123, 'foo' => 'bar']);
        return [];
        //var_dump($url);
        //exit;
        /*
        Ifw::$app->session->foo = false;
        Ifw::$app->session->bar = true;
        */
        // get one example:
        //$model = \app\modules\test\models\ActiveXyz::find()->where(['id' => 1])->asArray()->all();
        
        //return $model;
        /*
        $activeModel = new \test\models\ActiveXyz();
        $activeModel->street = 'Street 1';
        $activeModel->name = 'John Doe';
        if(!$activeModel->save()) {
           print_r($activeModel->getErrors());
        } else {
            $activeModel->name = 'Updated John Doe';
            if(!$activeModel->save()) {
                echo "after insert update row error";
                $activeModel->getErrors();
            } else {
                echo "UPDATED!";
            }
        }
        
        exit;
        
        $data = \test\models\ActiveXyz::find()->all();
        
        foreach($data as $row)
        {
            echo $row->id;
            echo $row->name;
            // change name
            $row->name = 'radan';
            $row->street = 'ra@da.n';
            
            if (!$row->save()) {
                echo "row save error:\n";
                print_r($row->getErrors());
            }
            $response = $row->validate();
            
            echo "<hr />";
        }
        */
    }
}
