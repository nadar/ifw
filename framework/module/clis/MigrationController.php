<?php

namespace ifw\module\clis;

use Ifw;

class MigrationController extends \ifw\cli\Controller
{
    public function actionIndex()
    {
        foreach(Ifw::$app->getModules() as $id => $module) {
            $module = Ifw::$app->getModule($id);
            //print_r($module);
            
            $files = \ifw\helpers\FileHelper::readdir($module->getBasePath() . DIRECTORY_SEPARATOR . 'tables');
            
            foreach($files as $file) {
                echo "compare: " . $file['file']. PHP_EOL;
                $class = pathinfo($file['name'], PATHINFO_FILENAME);
                
                try {
                    require_once($file['file']);
                    $obj = new $class();
                    $obj->compare();
                    
                } catch (\Exception $e) {
                    echo "error:" . $e->getMessage() . PHP_EOL;    
                }
            }
        }
    }
}