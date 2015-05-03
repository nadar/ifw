<?php

class Ifw
{
    public static $app = null;

    private static $_trace = [];

    /**
     * ifw init method.
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
     *
     * @return object
     */
    public static function init(array $config)
    {
        switch (strtolower(php_sapi_name())) {
            case 'cli':
                $className = '\\ifw\\cli\\Application';
                break;
            default:
                $className = '\\ifw\\web\\Application';
                break;
        }

        return static::$app = static::createObject($className, $config);
    }

    public static function trace($message)
    {
        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $item = array_shift($trace);
        static::$_trace[] = [
            'message' => $message,
            'file' => $item['file'],
            'line' => $item['line'],
            'time' => microtime(true),
        ];
    }

    public static function getTrace()
    {
        return array_reverse(static::$_trace);
    }

    /**
     * create a object based on a config array with existing class key or by className.
     *
     * @param string|array $class  Can be a class name (string) or an array with an existing class property, the other array values are used as params.
     * @param array        $params Class init propertys
     *
     * @throws \ifw\core\Exception
     *
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
