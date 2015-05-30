<?php

namespace ifw\components;

use Ifw;
use ifw\core\Exception;

/**
 * Query the datbase with an sql statement and fetch the results.
 *
 * The below example shows how the fetch all results into an array.
 *
 * ```php
 *     $data = \ifw::$app->db->command()->query("SELECT * FROM xyz")->fetchAll();
 * ``
 *
 * The faster approach the retrieve data row by row like this:
 * ```php
 * while($data = \ifw::$app->db->command()->query("SELECT * FROM xyz")->fetch()) {
 *     var_dump($data);
 * }
 * ``
 *
 * @author nadar
 */
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

    public function getConnection()
    {
        return $this->_dbo;
    }

    public function command()
    {
        try {
            return Ifw::createObject('\\ifw\\db\\Command', ['dbo' => $this->getConnection()]);
            //return $this;
        } catch (\PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
    
    public function tableSchema($table)
    {
        return Ifw::createObject('\\ifw\\db\\TableSchema', ['dbo' => $this->getConnection(), 'table' => $table]);
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
