<?php

namespace ifw\core;

use Ifw;

abstract class BaseTable extends \ifw\core\Object
{
    /**
     * @todo read class name and change to table convention
     */
    public function name()
    {
        return null;
    }
    
    /**
     * return [
     *       'id' => ['int' => 11, 'default' => 0],
     *       'firstname' => ['varchar' => 200, 'NOT NULL'],
     *       'lastname' => ['varchar' => 120, 'NOT NULL'],
     *       'mail' => ['varchar' => 80],
     *       'text' => ['text', 'NOT NULL']
     *   ];
     */
    abstract public function fields();
    
    /**
     * composite keys: PRIMARY KEY (t1ID, t2ID)
     * casual keys: PRIMARY KEY(id)
     * @return multitype:string
     */
    public function pk()
    {
        return[];
    }
    
    public function fk()
    {
        return [];
    }
    
    public function unique()
    {
        return [];
    }
    
    private function db()
    {
        return Ifw::$app->db->command();
    }
    
    private function queryCreateTable()
    {
        $sql = 'CREATE TABLE `' . $this->name() . '` (';
        foreach ($this->fields() as $fieldName => $opts) {
            
        }
        $sql.= ')';
        
        return $sql;
    }
    
    public function compare()
    {
        // see if table exists
        $shema = $this->db()->query('DESCRIBE '.$this->name())->fetchAll();
        if (count($shema) == 0) {
            $sql = $this->queryCreateTable();
            var_dump($sql);
            
            //$exec = $this->db()->query);
            //var_dump($exec);
        }
    }
}