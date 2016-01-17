<?php
namespace Progsmile\Validator\Rules;

use Progsmile\Validator\Contracts\Rules\RulesInterface;

class Alpha extends BaseRule implements RulesInterface
{
    public function isValid()
    {
        if ($this->isNotRequired()) {
            return true;
        }

        return is_string($this->params[1]) && preg_match('/^[\pL\pM]+$/u', $this->params[1]);
    }

    public function getMessage()
    {
        return 'Field :field: may only contain letters.';
    }
}
