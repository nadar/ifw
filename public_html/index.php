<?php

error_reporting(E_ALL);

require_once '../vendor/autoload.php';
require_once '../framework/ifw.php';

try {
    ifw::init([
        'basePath' => dirname(__DIR__),
        'defaultModule' => 'test',
        'components' => [
            'db' => [
                'dsn' => 'mysql:host=localhost;dbname=ifw',
                'user' => 'root',
                'password' => 'root',
            ]
        ],
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
