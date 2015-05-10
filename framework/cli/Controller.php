<?php

namespace ifw\cli;

/**
 * http://php.net/manual/en/features.commandline.php#73765 colors
 * http://php.net/manual/en/features.commandline.php#94924 prompt
 * @author nadar
 */
abstract class Controller extends \ifw\core\Controller
{
    protected function prompt($text)
    {
        echo $text;
        $handle = fopen ("php://stdin","r");
        $line = fgets($handle);
        return $line;
    }
}
