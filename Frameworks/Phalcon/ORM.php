<?php
namespace Progsmile\Validator\Frameworks\Phalcon;

use Phalcon\DI;
use Progsmile\Validator\Contracts\Frameworks\OrmInterface;

class ORM implements OrmInterface
{
    private $db;
    private $field;
    private $value;
    private $table;

    public function __construct($field, $value, $table)
    {
        $di = DI::getDefault();

        $this->db = $di->get('db');
        $this->field = $field;
        $this->value = $value;
        $this->table = $table;
    }

    public function isUnique()
    {
        // TODO: do a query now...
    }
}
