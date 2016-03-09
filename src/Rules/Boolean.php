<?php

namespace Progsmile\Validator\Rules;

class Boolean extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        $userValue = trim($this->getParams()[1]);

        return in_array($userValue, ['true', 'false']);
    }

    /**
     * Returns error message from rule.
     *
     * @return string
     */
    public function getMessage()
    {
        return 'Field :field: is not a boolean';
    }
}
