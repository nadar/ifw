<?php
namespace ifw\components;

use ifw\core\Exception;

class Db extends \ifw\core\Component
{
    public $dsn = null;
    
    public $user = null;
    
    public $password = null;
    
    private $_dbo = null;
    
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
        $query = $this->_dbo->prepare($statement);
        foreach($params as $key => $value) {
            $query->bindParam($key, $value);
        }
        $retun = $query->execute();
        $query->closeCursor();
        $query = null;
        return $return;
    }
    
    public function closeConnection()
    {
        $this->_dbo = null;
    }
}