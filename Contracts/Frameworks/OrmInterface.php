<?php
namespace Progsmile\Validator\Contracts\Frameworks;

interface OrmInterface
{
    public function __construct($field, $value, $table);

    /**
     * Check if the the value is unique based on the table's field
     *
     * @return boolean
     */
    public function isUnique();
}
