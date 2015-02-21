<?php
class ifw
{
    public static $app = null;
    
    public static function init(array $config = [])
    {
        return static::$app = new \ifw\core\Application($config);
    }
}