<?php

namespace ifw\helpers;

use Ifw;

class UrlHelper
{
    /**
     * @todo adding base url to url?
     * @param string $route
     * @param array $params
     */
    public static function to($route, array $params = [])
    {
        return Ifw::$app->routing->createUrl($route, $params);
    }
}