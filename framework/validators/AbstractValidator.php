<?php

namespace ifw\validators;

abstract class AbstractValidator extends \ifw\core\Object
{
    abstract public function run($value);
}
