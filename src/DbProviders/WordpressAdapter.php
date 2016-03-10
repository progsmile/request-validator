<?php

namespace Progsmile\Validator\DbProviders;

use Progsmile\Validator\Contracts\Rules\ExistInterface;
use Progsmile\Validator\Contracts\Rules\UniqueInterface;

class WordpressAdapter implements ExistInterface, UniqueInterface
{
    private $field;
    private $value;
    private $table;

    public function __construct($attribute, $value, $table)
    {
        $this->field = trim($attribute);
        $this->value = trim($value);
        $this->table = $table;
    }

    public function isUnique()
    {
        global $wpdb;

        $table = $wpdb->prefix.$this->table;

        $recordsCount = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM $table WHERE $this->field = %s", $this->value
            )
        );

        return $recordsCount == 0;
    }

    public function isExist()
    {
        return !$this->isUnique();
    }
}
