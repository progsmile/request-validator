<?php

namespace Progsmile\Validator\Rules;

class Max extends BaseRule
{
    private $isNumeric = false;

    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        if ($this->hasRule('numeric') !== false) {
            $this->isNumeric = true;

            return $this->getParams()[1] <= $this->getParams()[2] && is_numeric($this->getParams()[1]);
        }

        return is_string($this->getParams()[1]) && strlen($this->getParams()[1]) <= $this->getParams()[2];
    }

    public function getMessage()
    {
        if ($this->isNumeric) {
            return 'Field :field: must be less than or equal to :param:';
        }

        return 'Field :field: should be maximum of :param: characters';
    }
}
