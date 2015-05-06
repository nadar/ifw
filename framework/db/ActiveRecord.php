<?php

namespace ifw\db;

use Ifw;

/**
 * insert a new model record:.
 *
 * ```
 * $model = new \project\models\Test();
 * $model->firstname = 'John';
 * $model->lastname = 'Doe';
 * if (!$model->save()) {
 *     print_r($model->getErrors());
 * }
 * ```
 *
 * updated an existing record:
 *
 * ```
 * $model = new \project\models\Test::find()->where(['id' => 1])->one();
 * $model->firstname = 'Jane';
 * if (!$model->save()) {
 *     print_r($model->getErrors());
 * }
 * ```
 *
 * @author nadar
 */
class ActiveRecord extends \ifw\core\Model
{
    public $attributes = [];

    private $_isNewRecord = true;

    private $_fields = [];

    private $_pk = null;

    public function init()
    {
        parent::init();
        $shema  = \ifw::$app->db->command()->query('DESCRIBE '.static::tableName())->fetchAll();
        if (empty($shema)) {
            throw new \ifw\core\Exception("The ActiveRecord table '" . static::tableName() . "' does not exists.");
        }
        foreach ($shema as $field) {
            $this->_fields[] = $field['Field'];
            if ($field['Key'] == 'PRI') {
                $this->_pk = $field['Field'];
            }
        }
    }

    public function __set($key, $value)
    {
        if ($this->hasProperty($key)) {
            return parent::__set($key, $value);
        }

        if (!in_array($key, $this->_fields)) {
            throw new \ifw\core\Exception("the property '$key' does not exists as field in this table nor as property from this model class.");
        }

        $this->attributes[$key] = $value;
    }

    public function __get($key)
    {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }

        if ($this->hasProperty($key)) {
            return parent::__get($key);
        }

        return false;
        throw new \ifw\core\Exception("the requested key '$key' does not exists as property or attribute.");
    }

    public static function tableName()
    {
    }

    public static function find()
    {
        $name = static::className();

        return new \ifw\db\ActiveQuery(['modelClass' => $name]);
    }

    /**
     * @todo verify if the ar pk attribute is set!
     */
    public function setAsUpdateRecord()
    {
        $this->_isNewRecord = false;
        // !empty($this->getAttribute($this->_pk))
    }

    public function getPk()
    {
        return $this->_pk;
    }

    public function getPkValue()
    {
        return ($this->isNewRecord()) ? false : $this->getAttribute($this->getPk());
    }

    public function isNewRecord()
    {
        return $this->_isNewRecord;
    }

    public function save()
    {
        if ($this->validate()) {
            if ($this->isNewRecord()) {
                return $this->insert();
            } else {
                return $this->update();
            }
        }

        return false;
    }

    private function insert()
    {
        $values = [];
        foreach ($this->getSafeAttributes() as $attribute) {
            $values[$attribute] = $this->getAttribute($attribute);
        }

        $insert = Ifw::$app->db->command()->insert(static::tableName(), $values);

        if ($insert) {
            $this->setAttribute($this->getPk(), $insert);
            $this->setAsUpdateRecord();
            $this->resetValidation();

            return true;
        }

        return false;
    }

    private function update()
    {
        $values = [];
        foreach ($this->getSafeAttributes() as $attribute) {
            $values[$attribute] = $this->getAttribute($attribute);
        }

        $update = Ifw::$app->db->command()->update(static::tableName(), $values, [$this->getPk() => $this->getPkValue()]);
        if ($update) {
            $this->resetValidation();

            return true;
        }

        return false;
    }
}
