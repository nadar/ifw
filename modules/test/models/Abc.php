<?php
namespace test\models;

class Abc extends \ifw\core\Model
{
    public $title;
    
    public $name;
    
    public $mail;
    
    public $text;
    
    public function rules()
    {
        return [
            'required' => ['email', 'name'],
            ['email', ['email']],
            ['safe', ['title', 'text'], 'on' => 'default']
        ];
    }
}