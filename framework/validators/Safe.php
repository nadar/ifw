<?php
namespace ifw\validators;

class Safe extends \ifw\validators\AbstractValidator
{
    public function run($values)
    {
        return (empty($values)) ? false : true;
    }
}