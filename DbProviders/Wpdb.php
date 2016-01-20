<?php
namespace Progsmile\Validator\DbProviders;

use Progsmile\Validator\Contracts\Frameworks\OrmInterface;

class Wpdb implements OrmInterface
{
    private $field;
    private $value;
    private $table;

    public function __construct($attribute, $value, $table)
    {
        global $wpdb;

        $this->field = trim($attribute);
        $this->value = trim($value);
        $this->table = $wpdb->prefix . $table;
    }

    public function isUnique()
    {
        global $wpdb;

        $recordsCount = $wpdb->get_results(
           "SELECT COUNT(*) as c FROM $this->table WHERE $this->field = '$this->value'", ARRAY_A
        );

        return reset(reset($recordsCount)) == 0;
    }
}
