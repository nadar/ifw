<?php

namespace ifw\core;

use Ifw;
use Pdo;

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
     *       'id' => ['int' => 11, 'default 0'],
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
        $imp = [];
        foreach ($this->fields() as $fieldName => $opts) {
            $part = [];
            $part[] = $fieldName;
            foreach($opts as $k => $v) {
                if (is_int($k)) { // not field type
                    $part[] = $v;
                } else {
                    $part[] = "$k($v)";
                }
            }
            
            $imp[] = implode(" ", $part);
        }
        foreach($this->pk() as $key) {
            $imp[] = 'PRIMARY KEY (' . implode(",", $this->pk()) . ')';
        }
        $sql = 'CREATE TABLE `' . $this->name() . '` (' .implode(', ', $imp).')';
        
        return $sql;
    }
    
    public function compare()
    {
        // see if table exists
        $shema = $this->db()->query('DESCRIBE '.$this->name())->fetchAll();
        if (count($shema) == 0) {
            $sql = $this->queryCreateTable();
            var_dump($sql);
            try {
                $db = Ifw::$app->db->getConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $r = $db->exec($sql);
                var_dump($r);
                
            } catch (Exception $e) {
                var_dump($e->getMessage);
            }
            /*
            $response = $this->db()->query($sql)->getStatement();
            var_dump($response);
            */
            //$exec = $this->db()->query);
            //var_dump($exec);
        } else {
            echo "TABLE EXISTS... compare";
        }
    }
}