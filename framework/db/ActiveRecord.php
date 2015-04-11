<?php
namespace ifw\db;

class ActiveRecord extends \ifw\core\Model
{
    public static function tableName()
    {
        
    }
    
    public static function find()
    {
        return new \ifw\db\ActiveQuery(['table' => static::tableName()]);
    }
}