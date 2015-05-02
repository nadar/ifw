<?php

error_reporting(E_ALL);

require_once '../vendor/autoload.php';
require_once '../framework/ifw.php';

try {
    
    $config = new \ifw\Config(dirname(__DIR__), 'test');
    $config->module('test', '\\app\\modules\\test\\Module');
    $config->component('db', ['dsn' => 'mysql:host=localhost;dbname=ifw', 'user' => 'root', 'password' => 'root']);
    
    ifw::init($config->get());

    echo ifw::$app->run();
    
    //print_r(ifw::getTrace());
    
} catch (Exception $e) {
    echo "<pre>";
    print_r($e);
    echo "</pre>";
}
