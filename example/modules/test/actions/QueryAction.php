<?php
namespace test\actions;

use Ifw;

class QueryAction extends \ifw\core\Action
{
    public function run()
    {
        $data = (new \ifw\db\Query())->select(['id', 'name', 'street' => 'Strasse'])->from('xyz')->where(['id' => 1])->query();
        
        var_dump($data->fetchAll());
    }
}
