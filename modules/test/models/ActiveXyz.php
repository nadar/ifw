<?php
namespace test\models;

class ActiveXyz extends \ifw\db\ActiveRecord
{
    public static function tableName()
    {
        return 'xyz';
    }
    
    public function rules()
    {
        return [
            "required" => ['name', 'street']
        ];
    }
}