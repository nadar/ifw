<?php

namespace ifw\helpers;

class ArrayHelper
{
    public static function toObject(array $array)
    {
        return json_decode(json_encode($array), false);
    }
}
