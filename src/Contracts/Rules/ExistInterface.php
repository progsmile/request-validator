<?php

namespace Progsmile\Validator\Contracts\Rules;

interface ExistInterface
{
    /**
     * Check if the the value exists based on the table's field.
     *
     * @return bool
     */
    public function isExist();
}
