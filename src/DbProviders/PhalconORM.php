<?php
namespace Progsmile\Validator\DbProviders;

use Progsmile\Validator\Contracts\Frameworks\OrmInterface;
use Phalcon\DI;

class PhalconORM implements OrmInterface
{
    private $db;
    private $field;
    private $value;
    private $table;

    public function __construct($attribute, $value, $table)
    {
        $di = DI::getDefault();

        $this->db    = $di->get('db');
        $this->field = $attribute;
        $this->value = $value;
        $this->table = $table;
    }

    public function isUnique()
    {
        $recordsCount = $this->db->fetchColumn(
            "SELECT COUNT(*) FROM $this->table WHERE $this->field = ?", [$this->value]
        );

        return $recordsCount == 0;
    }

    public function isExist()
    {
        return !$this->isUnique();
    }
}
