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

        $recordsCount = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM $this->table WHERE $this->field = %s",  $this->value
            )
        );

        return $recordsCount == 0;
    }
}
