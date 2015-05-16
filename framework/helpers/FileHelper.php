<?php

namespace ifw\helpers;

class FileHelper
{
    public static function copy($source, $destination)
    {
        
    }
    
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
    
    public static function readdir($dir)
    {
        if (!file_exists($dir)) {
            return [];
        }
        $files = [];
        foreach(scandir($dir, SCANDIR_SORT_ASCENDING) as $item) {
            if (substr($item, 0, 1) !== '.') {
                $files[] = [
                    'file' => $dir . DIRECTORY_SEPARATOR . $item,
                    'name' => $item,
                    'dir' => $dir,
                ];
            }
        }
        
        return $files;
    } 
}
