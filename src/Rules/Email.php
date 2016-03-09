<?php

namespace Progsmile\Validator\Rules;

class Email extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        return filter_var($this->getParams()[1], FILTER_VALIDATE_EMAIL);
    }

    /**
     * Returns error message from rule.
     *
     * @return string
     */
    public function getMessage()
    {
        return 'Field :field: has a bad email format';
    }
}
