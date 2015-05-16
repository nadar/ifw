<?php

class User extends \ifw\core\BaseTable
{
    public function name()
    {
        return 'tbl_user';
    }
    
    public function fields()
    {
        return [
            'id' => ['int' => 11, 'default' => 0],
            'firstname' => ['varchar' => 200, 'NOT NULL'],
            'lastname' => ['varchar' => 120, 'NOT NULL'],
            'mail' => ['varchar' => 80],
            'text' => ['text', 'NOT NULL']
        ];
    }
    
    /**
     * composite keys: PRIMARY KEY (t1ID, t2ID)
     * casual keys: PRIMARY KEY(id)
     * @return multitype:string
     */
    public function pk()
    {
        return [
            'id'
        ];
    }
    
    public function unique()
    {
        return [
            'mail'
        ];
    } 
}