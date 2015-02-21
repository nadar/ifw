<?php

error_reporting(E_ALL);

require_once '../vendor/autoload.php';
require_once '../framework/ifw.php';

try {
    
    ifw::init([
        'modules' => [
            'test' => [
                'class' => '\\test\\Module'
            ]
        ]
    ]);
    
    echo ifw::$app->run();
    
} catch (Exception $e) {
    var_dump($e->getMessage());
}