<?php

namespace Progsmile\Validator\Rules;

class Ip extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        $input = trim($this->getParams()[1]);

        return $this->respect('Ip')->validate($input);
    }

    public function getMessage()
    {
        return 'Field :field: is not valid IP address';
    }
}
