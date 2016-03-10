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

        $input = trim($this->getParams()[1]);
        $value = trim($this->getParams()[2]);

        if ($this->hasRule('numeric') !== false && is_numeric($input)) {
            $this->isNumeric = true;

            return $this->respect('Max', [$value, true])->validate($input);
        }

        // there is no way respect/validator supports string for rule 'Max'
        return is_string($input) && strlen($input) <= $value;
    }

    public function getMessage()
    {
        if ($this->isNumeric) {
            return 'Field :field: must be less than or equal to :param:';
        }

        return 'Field :field: should be maximum of :param: characters';
    }
}
