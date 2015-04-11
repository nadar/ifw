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
            'required' => ['mail', 'name'],
            ['email', ['mail']],
            ['safe', ['title', 'text'], 'on' => 'default']
        ];
    }
}