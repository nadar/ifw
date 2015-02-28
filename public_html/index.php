<?php

error_reporting(E_ALL);

require_once '../vendor/autoload.php';
require_once '../framework/ifw.php';

try {
    ifw::init([
        'basePath' => dirname(__DIR__),
        'defaultModule' => 'test',
        'modules' => [
            'test' => [
                'class' => '\\test\\Module',
            ],
        ],
    ]);

    echo ifw::$app->run();
} catch (Exception $e) {
    echo "<pre>";
    print_r($e);
    echo "</pre>";
}
