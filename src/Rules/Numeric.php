<?php

namespace Progsmile\Validator\Rules;

class Numeric extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        return is_numeric($this->getParams()[1]);
    }

    public function getMessage()
    {
        return 'Field :field: is not a number';
    }
}
