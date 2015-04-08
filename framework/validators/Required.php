<?php
namespace ifw\validators;

class Required extends \ifw\validators\AbstractValidator
{
    public function run($values)
    {
        return (empty($values)) ? false : true;
    }
}