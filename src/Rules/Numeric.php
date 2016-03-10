<?php

namespace Progsmile\Validator\Rules;

class Numeric extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        return $this->respect('Numeric')->validate(trim($this->getParams()[1]));
    }

    public function getMessage()
    {
        return 'Field :field: is not a number';
    }
}
