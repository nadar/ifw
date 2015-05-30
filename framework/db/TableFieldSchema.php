<?php

namespace ifw\db;

class TableFieldSchema extends \ifw\core\Object
{
    protected $Field = null;

    protected $Type = null;
    
    protected $Null = null;
    
    protected $Key = null;
    
    protected $Default = null;
    
    protected $Extra = null;
    
    public function getName()
    {
        return $this->Field;
    }
}