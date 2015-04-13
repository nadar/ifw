<?php
class Ifw
{
    public static $app = null;

    public static function init(array $config = [])
    {
        return static::$app = static::createObject('\ifw\core\Application', $config);
    }
    
    public static function createObject($class, array $params = [])
    {
        return new $class($params);
    }
}
