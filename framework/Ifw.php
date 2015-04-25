<?php

class Ifw
{
    public static $app = null;

    /**
     * ifw init method
     * 
     * example config
     * ```
     * ifw::init([
     *   'basePath' => dirname(__DIR__),
     *   'defaultModule' => 'foobar',
     *   'components' => [
     *       'db' => [
     *           'dsn' => 'mysql:host=localhost;dbname=DATABASE',
     *           'user' => 'USERNAME',
     *           'password' => 'PASSWORD',
     *       ]
     *   ],
     *   'modules' => [
     *       'foobar' => [
     *           'class' => '\\app\\modules\\foobar\\Module',
     *       ],
     *   ],
     * ]);
     * ```
     * 
     * @param array $config
     * @return object
     */
    public static function init(array $config)
    {
        return static::$app = static::createObject('\ifw\core\Application', $config);
    }
    
    /**
     * create a object based on a config array with existing class key or by className.
     * 
     * @param string|array $class Can be a class name (string) or an array with an existing class property, the other array values are used as params.
     * @param array $params Class init propertys
     * @throws \ifw\core\Exception
     * @return object
     */
    public static function createObject($class, array $params = [])
    {
        if (is_array($class)) {
            if (!array_key_exists('class', $class)) {
                throw new \ifw\core\Exception('The class property does not exists in the to create object.');
            }
            
            $className = $class['class'];
            unset($class['class']);
            $params = array_merge($class, $params);
        } else {
            $className = $class;
        }
        
        return new $className($params);
    }
}