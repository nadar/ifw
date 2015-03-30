<?php
namespace ifw\components;

use ifw\core\Exception;

class Db extends \ifw\core\Component
{
    public $dsn = null;
    
    public $user = null;
    
    public $password = null;
    
    private $_dbo = null;
    
    private $_stmt = null;
    
    public function init()
    {
        $this->setConnection();
    }
    
    public function setConnection()
    {
        try {
            $this->_dbo = new \PDO($this->dsn, $this->user, $this->password);
        } catch (\PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
    
    public function query($statement, array $params = [])
    {
        $this->_stmt = null;
        $this->_stmt = $this->_dbo->prepare($statement);
        foreach($params as $key => $value) {
            $query->bindParam($key, $value);
        }
        $this->_stmt->execute();
        return $this;
    }
    
    public function getStatement()
    {
        return $this->_stmt;
    }
    
    public function fetch($fetchMode = \PDO::FETCH_ASSOC)
    {
        return $this->_stmt->fetch($fetchMode);
    }
    
    public function fetchAll($fetchMode = \PDO::FETCH_ASSOC)
    {
        $response = $this->_stmt->fetchAll($fetchMode);
        $this->_stmt->closeCursor();
        $this->_stmt = null;
        return $response;
    }
    
    public function closeConnection()
    {
        $this->_dbo = null;
    }
    
    public function __destruct()
    {
        $this->closeConnection();
    }
}