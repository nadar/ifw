<?php
namespace ifw\db;

class ActiveQuery extends \ifw\core\Component
{
    public $table = null;
    
    public function all()
    {
        $query = (new \ifw\db\Query())->from($this->table)->query()->fetchAll();
        return $query;
    }
    
    public function one()
    {
        
    }
}