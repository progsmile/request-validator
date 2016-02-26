<?php

namespace Progsmile\Validator\Rules;

class Alpha extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        $value = trim($this->params[1]);

        return is_string($value) && preg_match('/^[\pL\pM]+$/u', $value);
    }

    /**
     * Returns error message from rule.
     *
     * @return string
     */
    public function getMessage()
    {
        return 'Field :field: may only contain letters';
    }
}
