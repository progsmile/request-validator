<?php

namespace Progsmile\Validator\Rules;

class Min extends BaseRule
{
    private $isNumeric = false;

    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        $userValue = $this->getParams()[1];

        //if `numeric` rule founds - validate as a number
        if ($this->hasRule('numeric') !== false) {
            $this->isNumeric = true;

            return $userValue >= $this->getParams()[2] && is_numeric($userValue);
        }

        return is_string($userValue) && strlen($userValue) >= $this->getParams()[2];
    }

    public function getMessage()
    {
        if ($this->isNumeric) {
            return 'Field :field: should be grater than :param:';
        }

        return 'Field :field: should be at least :param: characters';
    }
}
