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
    
    private function fieldSchema($fieldName, $opts)
    {
        $part = [];
        $part[] = $fieldName;
        foreach($opts as $k => $v) {
            if (is_int($k)) { // not field type
                $part[] = $v;
            } else {
                $part[] = "$k($v)";
            }
        }
        
        return implode(" ", $part);
    }
    
    private function fieldStructure($opts)
    {
        $part = [];
        foreach($opts as $k => $v) {
            if (is_int($k)) { // not field type
                $part[] = $v;
            } else {
                $part[] = "$k($v)";
            }
        }
        
        return implode(" ", $part);
    }
    
    private function queryCreateTable()
    {
        $imp = [];
        foreach ($this->fields() as $fieldName => $opts) {
            $imp[] = $this->fieldSchema($fieldName, $opts);
        }
        foreach($this->pk() as $key) {
            $imp[] = 'PRIMARY KEY (' . implode(",", $this->pk()) . ')';
        }
        $sql = 'CREATE TABLE `' . $this->name() . '` (' .implode(', ', $imp).')';
        
        return $sql;
    }
    
    public function compare()
    {
        $table = Ifw::$app->db->tableSchema($this->name());
        
        if (!$table->exists()) {
            $sql = $this->queryCreateTable();
            try {
                $db = Ifw::$app->db->getConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $response = $db->exec($sql);
            } catch (Exception $e) {
                var_dump($e->getMessage);
            }
        } else {
            foreach($this->fields() as $name => $props) {
                if ($table->existField($name)) {
                    //echo "$name existiert" . PHP_EOL;
                    
                } else {
                    $table->addField($name, $this->fieldStructure($props));
                    //echo "$name existiert noch nicht!" . PHP_EOL;
                }
            }
        }
    }
}