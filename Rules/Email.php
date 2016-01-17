<?php
namespace Progsmile\Validator\Rules;

use Progsmile\Validator\Contracts\Rules\RulesInterface;

class Email extends BaseRule implements RulesInterface
{
    public function isValid()
    {
        if ($this->isNotRequired()) {
            return true;
        }

        return (bool) filter_var($this->params[1], FILTER_VALIDATE_EMAIL);
    }

    public function getMessage()
    {
        return 'Field :field: has a bad email format.';
    }
}
