<?php

namespace Progsmile\Validator\Rules;

class Email extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        return $this->respect('Email')->validate($this->getParams()[1]);
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
