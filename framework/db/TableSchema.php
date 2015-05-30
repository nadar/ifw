<?php

namespace ifw\db;

use PDO;

/**
 * 
 * @author nadar
 *
 * ```
 * $schema = new TableSchema()
 * $schema->dbo = (new DBO());
 * foreach($schema->fields as $field) {
 *     var_dump($field); // see \ifw\db\TableFieldSchema classsd
 * }
 * ```
 */
class TableSchema extends \ifw\core\Object
{
    public $dbo = null;
    
    public $table = null;
    
    private $_fields = null;
    
    private $_internalFields = null;
    
    private $_exists = false;
    
    public function exists()
    {
        $fields = $this->getFields();
        
        return $this->_exists;
    }
    
    public function getField($name)
    {
        $fields = $this->getFields();
        
        foreach($fields as $field) {
            if ($field->name == $name) {
                return $field;
            }
        }
        
        return false;
    }
    
    public function existField($name)
    {
        $fields = $this->getFields();
        
        foreach($fields as $field) {
            if ($field->name == $name) {
                return true;
            }
        }
        
        return false;
    }
    
    public function getFields()
    {
        if ($this->_fields === null) {
            $q = $this->dbo->prepare('DESCRIBE ' . $this->table);
            $this->_exists = $q->execute();
            $q->setFetchMode(PDO::FETCH_ASSOC);
            $this->_fields = $q->fetchAll(PDO::FETCH_CLASS, '\\ifw\\db\\TableFieldSchema');
        }
        
        return $this->_fields;
    }
}