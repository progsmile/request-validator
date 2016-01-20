<?php
namespace Progsmile\Validator\Rules;

class Ip extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequired()) {
            return true;
        }

        return filter_var($this->params[1], FILTER_VALIDATE_IP) !== false;
    }

    public function getMessage()
    {
        return 'Field :field: is not valid IP address.';
    }
}