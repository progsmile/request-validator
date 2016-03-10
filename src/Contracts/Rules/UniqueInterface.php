<?php

namespace Progsmile\Validator\Contracts\Rules;

interface UniqueInterface
{
    /**
     * Check if the the value is unique based on the table's field.
     *
     * @return bool
     */
    public function isUnique();
}
