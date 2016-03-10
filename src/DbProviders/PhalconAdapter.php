<?php

namespace Progsmile\Validator\DbProviders;

use Phalcon\DI;
use Progsmile\Validator\Contracts\Rules\ExistInterface;
use Progsmile\Validator\Contracts\Rules\UniqueInterface;

class PhalconAdapter extends Adapter implements ExistInterface, UniqueInterface
{
    private $db;
    private $field;
    private $value;
    private $table;

    public function __construct($attribute, $value, $table)
    {
        if (!extension_loaded('phalcon')) {
            throw new \RuntimeException('The phalcon extension is not loaded');
        }

        $di = DI::getDefault();

        if (!$di->has('db')) {
            throw new \RuntimeException('The db service is required for '.__CLASS__);
        }

        $this->db = $di->getShared('db');
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
