<?php
namespace Progsmile\Validator\Contracts\Frameworks;

interface OrmInterface
{
    public function __construct($attribute, $value, $table);

    /**
     * Check if the the value is unique based on the table's field
     *
     * @return boolean
     */
    public function isUnique();


    /**
     * Check if the the value exists based on the table's field
     *
     * @return boolean
     */
    public function isExist();
}
