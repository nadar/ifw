<?php

namespace ifw\db;

use Ifw;

/**
 * example of query building
 * ```php
 *  $data = (new \ifw\db\Query())->select(['id', 'name', 'street' => 'street_alias'])->from('xyz')->where(['id' => 0])->query();
 * ```.
 *
 * @author nadar
 */
class Query extends \ifw\core\Object
{
    private $_params = [];

    private $_select = [];

    private $_from = null;

    private $_where = [];

    private function getParams()
    {
        return $this->_params;
    }

    public function from($tableName)
    {
        $this->_from = $tableName;

        return $this;
    }

    private function getFrom()
    {
        return $this->_from;
    }

    public function select(array $select)
    {
        $this->_select = $select;

        return $this;
    }

    private function getSelect()
    {
        if (empty($this->_select)) {
            return '*';
        }
        $_vars = [];
        foreach ($this->_select as $k => $v) {
            if (is_numeric($k)) {
                // no alias
                $_vars[] = $v;
            } else {
                // alias
                $_vars[] = "$k as $v";
            }
        }

        return implode(', ', $_vars);
    }

    /**
     * @param array $where ['key' => 'value']
     * @param $operator AND/OR
     *
     * @return \ifw\db\Query
     */
    public function where(array $where, $operator = 'AND')
    {
        foreach ($where as $key => $value) {
            $this->_where[] = [
                'field' => $key,
                'value' => $value,
                'operator' => $operator,
            ];
        }

        return $this;
    }

    private function addParam($param, $value)
    {
        $this->_params[$param] = $value;
    }

    private function getWhere()
    {
        if (empty($this->_where)) {
            return '';
        }
        $_vars = [];
        $i = 1;
        foreach ($this->_where as $item) {
            $str = $item['field'].' = :'.$item['field'];
            if ($i < count($this->_where)) {
                $str .= ' '.$item['operator'].' ';
            }
            $_vars[] = $str;
            $this->addparam($item['field'], $item['value']);

            $i++;
        }

        return ' WHERE '.implode(' ', $_vars);
    }

    /**
     * creates.
     *
     * SELECT foo,bar as baz FROM `tableName`where a=1
     */
    public function getStatement()
    {
        return 'SELECT '.$this->getSelect().' FROM `'.$this->getFrom().'`'.$this->getWhere();
    }

    public function query()
    {
        return Ifw::$app->db->command()->query($this->getStatement(), $this->getParams());
    }
}
