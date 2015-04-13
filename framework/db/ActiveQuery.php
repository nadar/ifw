<?php
namespace ifw\db;

class ActiveQuery extends \ifw\core\Component
{
    public $modelClass = null;
    
    //public $tableName = null;
    
    private $_query = null;
    
    public function init()
    {
        $class = $this->modelClass;
        //$this->tableName = $class::tableName();
        $this->_query = (new \ifw\db\Query())->from($class::tableName());
        parent::init();
    }
    
    private function getQuery()
    {
        return $this->_query;
    }
    
    public function where(array $where, $whereOperator = 'AND')
    {
        $this->getQuery()->where($where, $whereOperator);
        
        return $this;
    }
    
    public function all()
    {
        $models = [];
        $query = $this->getQuery()->query();
        
        while($row = $query->fetch()) {
            $models[] = $this->createModel($row);
        }
        return $models;
        
    }
    
    public function one()
    {
        $model = null;
        $query = $this->getQuery()->query();
        $row = $query->fetch();
        return $this->createModel($row);
    }
    
    private function createModel($row)
    {
        $obj = \ifw::createObject($this->modelClass);
        $obj->setAsUpdateRecord();
        $obj->attributes = $row;
        return $obj;
    }
}