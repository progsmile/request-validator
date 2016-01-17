<?php
namespace Progsmile\Validator\Rules;

use Progsmile\Validator\Contracts\Rules\RulesInterface;

class Ip extends BaseRule implements RulesInterface
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