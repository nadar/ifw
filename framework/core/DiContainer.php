<?php

namespace ifw\core;

use Ifw;

class DiContainer
{
    public static $containers = [];

    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * We have no add() and append() method, we only use an append() method, cause its common
     * to merge di container params before instanciate with get($id).
     *
     * Always remove the class parameter from the params array if there is any.
     */
    public function append($id, $className = null, array $params = [])
    {
        if (isset($params['class'])) {
            unset($params['class']);
        }

        if ($this->has($id)) {
            static::$containers[$id] = [
                'className' => $className,
                'params' => array_merge(static::$containers[$id]['params'], $params),
                'object' => null,
            ];
        } else {
            static::$containers[$id] = [
                'id' => $id,
                'className' => $className,
                'params' => $params,
                'object' => null,
            ];
        }
    }

    public function get($id)
    {
        if (!$this->has($id)) {
            throw new \Exception('requested container does not exists.');
        }

        if (is_null(static::$containers[$id]['object'])) {
            Ifw::trace("di create class object '".static::$containers[$id]['className']."'");

            static::$containers[$id]['object'] = new static::$containers[$id]['className'](static::$containers[$id]['params']);
        }

        return static::$containers[$id]['object'];
    }

    public function has($id)
    {
        return (isset(static::$containers[$id])) ? true : false;
    }
}
