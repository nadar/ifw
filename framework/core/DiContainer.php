<?php
namespace ifw\core;

class DiContainer
{
    public static $containers = [];

    public function __get($key)
    {
        return $this->get($key);
    }

    public function add($id, $className, array $params = [])
    {
        if ($this->has($id)) {
            throw new \Exception('container already registered');
        }

        static::$containers[$id] = [
            'id' => $id,
            'className' => $className,
            'params' => $params,
            'object' => null,
        ];
    }

    public function get($id)
    {
        if (!$this->has($id)) {
            throw new \Exception('requested container does not exists.');
        }

        if (is_null(static::$containers[$id]['object'])) {
            static::$containers[$id]['object'] = new static::$containers[$id]['className'](static::$containers[$id]['params']);
        }

        return static::$containers[$id]['object'];
    }

    public function has($id)
    {
        return (isset(static::$containers[$id])) ? true : false;
    }
}
