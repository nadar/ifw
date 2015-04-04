<?php
namespace test\models;

class Abc extends \ifw\core\Model
{
    public $var1;
    
    public $var2;
    
    public $text;
    
    public function rules()
    {
        return [
            'required' => ['prop1', 'prop2'],
            ['mail', ['prop1']],
            ['safe', ['prop1', 'prop2'], 'on' => 'default']
        ];
    }
}