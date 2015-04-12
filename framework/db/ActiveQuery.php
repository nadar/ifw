<?php
namespace ifw\db;

class ActiveQuery extends \ifw\core\Component
{
    public $modelClass = null;
    
    public $tableName = null;
    
    public function init()
    {
        $class = $this->modelClass;
        $this->tableName = $class::tableName();
        parent::init();
    }
    
    public function all()
    {
        $models = [];
        $query = (new \ifw\db\Query())->from($this->tableName)->query();
        while($row = $query->fetch()) {
            $models[] = $this->createModel($row);
        }
        return $models;
        
    }
    
    public function one()
    {
        
    }
    
    private function createModel($row)
    {
        $className = $this->modelClass;
        $obj = new $className();
        $obj->attributes = $row;
        return $obj;
    }
}