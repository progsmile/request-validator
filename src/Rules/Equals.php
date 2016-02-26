<?php

namespace Progsmile\Validator\Rules;

class Equals extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        return trim($this->params[1]) == trim($this->params[2]);
    }

    public function getMessage()
    {
        return 'Field :field: has wrong value';
    }
}
