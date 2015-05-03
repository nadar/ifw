<?php

namespace ifw\helpers;

class FileHelper
{
    public static function copyr($source, $destination, array $fileTypeFilter = ['css', 'js', 'png', 'jpg', 'gif', 'less'])
    {
        $dir = opendir($source);
        @mkdir($destination);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($source.DIRECTORY_SEPARATOR.$file)) {
                    static::copyr($source.DIRECTORY_SEPARATOR.$file, $destination.DIRECTORY_SEPARATOR.$file);
                } else {
                    $fileType = pathinfo($source.DIRECTORY_SEPARATOR.$file, PATHINFO_EXTENSION);
                    if (in_array(strtolower($fileType), $fileTypeFilter)) {
                        copy($source.DIRECTORY_SEPARATOR.$file, $destination.DIRECTORY_SEPARATOR.$file);
                    }
                }
            }
        }
        closedir($dir);
    }
}
