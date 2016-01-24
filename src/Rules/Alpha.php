<?php
namespace Progsmile\Validator\Rules;

class Alpha extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        return is_string($this->params[1]) && preg_match('/^[\pL\pM]+$/u', $this->params[1]);
    }

    public function getMessage()
    {
        return 'Field :field: may only contain letters.';
    }
}
