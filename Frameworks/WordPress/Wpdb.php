<?php
namespace Progsmile\Validator\Frameworks\WordPress;

use Progsmile\Validator\Contracts\Frameworks\OrmInterface;

class Wpdb implements OrmInterface
{
    private $field;
    private $value;
    private $table;

    public function __construct($field, $value, $table)
    {
        global $wpdb;

        $this->field = trim($field);
        $this->value = trim($value);
        $this->table = $wpdb->prefix . $table;
    }

    public function isUnique()
    {
        global $wpdb;

        $res = $wpdb->get_results(
           "SELECT COUNT(*) as c FROM $this->table WHERE $this->field = '$this->value'", ARRAY_A
        );

        return reset(reset($res)) == '0';
    }
}
