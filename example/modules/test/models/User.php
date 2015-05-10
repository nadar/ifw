<?php

namespace app\modules\test\models;

class User extends \ifw\db\ActiveRecord
{
    public static function tableName()
    {
        return 'user';
    }
}