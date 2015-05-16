<?php

namespace ifw\module\clis;

use Ifw;

class HelpController extends \ifw\cli\Controller
{
    public function actionIndex()
    {
        echo 'Commands: migration';
    }
}