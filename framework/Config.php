<?php

namespace ifw;

/**
 * 
 * The below example config
 * ```
 * [
 *      'basePath' => dirname(__DIR__),
 *      'defaultModule' => 'test',
 *      'components' => [
 *          'db' => [
 *              'dsn' => 'mysql:host=localhost;dbname=DATABASE',
 *              'user' => 'USER',
 *              'password' => 'PASSWORD',
 *          ]
 *      ],
 *      'modules' => [
 *          'test' => [
 *              'class' => '\\app\\modules\\test\\Module',
 *          ],
 *      ],
 * ]
 * ```
 * can be builded with the Config class like this:
 * ```
 * $config = new \ifw\Config(dirname(__DIR__), 'test');
 * $config->module('test', '\\app\\modules\\test\\Module');
 * $config->component('db', ['dsn' => 'mysql:host=localhost;dbname=DATABASE', 'user' => 'USER', 'password' => 'PASSWORD']);
 * $config->get(); // returns the array
 * ```
 */
class Config implements \ArrayAccess
{
    public $config = [
        'basePath' => null,
        'defaultModule' => null,
        'modules' => [],
        'components' => [],
    ];
    
    public function __construct($basePath, $defaultModule)
    {
        $this->config = [
            'basePath' => $basePath,
            'defaultModule' => $defaultModule  
        ];
    }
    
    public function offsetSet($offset, $value)
    {
        if (empty($offset)) {
            throw new \Exception("the offset key can not be empty.");
        }
        
        $this->config[$offset] = $value;
    }
    
    public function offsetExists($offset) {
        return isset($this->config[$offset]);
    }
    
    public function offsetUnset($offset) {
        unset($this->config[$offset]);
    }
    
    public function offsetGet($offset) {
        return isset($this->config[$offset]) ? $this->config[$offset] : null;
    }
    
    public function module($name, $className, array $args = [])
    {
        if ($this->has("modules.$name")) {
            throw new \Exception("the module does already exist.");
        }
        $objectConfig = $args;
        $objectConfig['class'] = $className;
        $this->config['modules'][$name] = $objectConfig;
    }
    
    public function component($name, array $args = [], $className = null)
    {
        if ($this->has("components.$name")) {
            throw new \Exception("the component does already exist.");
        }
        
        $objectConfig = $args;
        if ($className !== null) {
            $objectConfig['class'] = $className;
        }
        $this->config['components'][$name] = $objectConfig;
    }
    
    public function has($key)
    {
        $keys = explode(".", $key);
        $array = $this->config;
        $i = 0;
        foreach ($keys as $name) {
            if (array_key_exists($name, $array)) {
                $array = $array[$name];
            } else {
                return false;
            }
            $i++;
        }
        
        return true;
    }
    
    public function get()
    {
        return $this->config;
    }
    
}