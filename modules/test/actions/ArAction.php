<?php
namespace test\actions;

use Ifw;

class ArAction extends \ifw\core\Action
{
    public function run()
    {
        $activeModel = new \test\models\ActiveXyz();
        $data = $activeModel::find()->all();
        
        foreach($data as $row)
        {
            echo $row->id;
            echo $row->name;
            // change name
            $row->name = 'max';
            $response = $row->validate();
            if (!$row->validate()) {
                var_dump($row->getErrors());
            }
            echo $row->name;
            print_r($row);
            echo "<hr />";
        }
    }
}
