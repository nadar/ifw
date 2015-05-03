<?php

namespace ifw\validators;

class Email extends \ifw\validators\AbstractValidator
{
    public function run($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}
