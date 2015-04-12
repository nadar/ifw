<?php
namespace ifw\db;

class ActiveRecord extends \ifw\core\Model
{
    public $attributes = [];
    
    public function __set($key, $value)
    {
        if ($this->hasProperty($key)) {
            return parent::__set($key, $value);
        }
        
        $this->attributes[$key] = $value;
    }
    
    public function __get($key)
    {
        if ($this->hasProperty($key)) {
            return parent::__get($key);
        }
        
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }
        
        throw new \ifw\core\Exception("the requested key $key does not exists as property or attribute.");
    }
    
    public static function tableName()
    {
        
    }
    
    public static function find()
    {
        $name = static::className();
        return new \ifw\db\ActiveQuery(['modelClass' => $name]);
    }
    
    
}