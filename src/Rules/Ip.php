<?php

namespace Progsmile\Validator\Rules;

class Ip extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        return filter_var($this->getParams()[1], FILTER_VALIDATE_IP) !== false;
    }

    public function getMessage()
    {
        return 'Field :field: is not valid IP address';
    }
}
