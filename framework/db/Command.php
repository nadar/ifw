<?php
namespace ifw\db;

class Command extends \ifw\core\Object
{
    public $statement = null;
    
    public $dbo = null;
    
    public function insert($tableName, array $params)
    {
        $keys = [];
        $values = [];
        foreach ($params as $key => $value) {
            $keys[] = $key;
            $values[] = ":" . $key;
        }
        $this->statement = $this->dbo->prepare("INSERT INTO `$tableName` (".implode(", ", $keys).") VALUES (".implode(", ", $values).")");
        foreach ($params as $key => &$value) {
            $this->statement->bindParam(":" . $key, $value);
        }
        $exec = $this->statement->execute();
        return $this->dbo->lastInsertId();
    }
    
    /**
     * 
     * @param unknown $tableName e.g. users
     * @param array $params ['name' => 'new name value']
     * @param array $where ['id' => 1]
     */
    public function update($tableName, array $params, array $where, $whereOperator = 'AND')
    {
        $sets = [];
        foreach ($params as $key => $value) {
            $sets[] = "$key=:$key";
        }
        $wheres = [];
        foreach ($where as $key => $value) {
            $wheres[] = "$key=:$key";
        }
        
        $this->statement = $this->dbo->prepare("UPDATE `$tableName` SET ".implode(", ", $sets)." WHERE " . implode(" $whereOperator ", $wheres));
        
        foreach ($params as $key => &$value) {
            $this->statement->bindParam(":$key", $value);
        }
        
        foreach ($where as $key => &$value) {
            $this->statement->bindParam(":$key", $value);
        }
        
        return $this->statement->execute();
    }
    
    public function query($statement, array $params = [])
    {
        $this->statement = $this->dbo->prepare($statement);
        
        foreach($params as $key => &$value) {
            $this->statement->bindParam(":" . $key, $value);
        }
        $this->statement->execute();
        
        return $this;
    }
    
    public function fetch($fetchMode = \PDO::FETCH_ASSOC)
    {
        return $this->statement->fetch($fetchMode);
    }
    
    public function fetchAll($fetchMode = \PDO::FETCH_ASSOC)
    {
        return $this->statement->fetchAll($fetchMode);
    }

    public function getErrorInfo()
    {
        return $this->statement->errorInfo();
    }
}