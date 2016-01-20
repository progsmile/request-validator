<?php
namespace Progsmile\Validator\Rules;

class Required extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequired()) {
            return true;
        }

        return (bool) $this->params[1];
    }

    public function getMessage()
    {
        return 'Field :field: is required';
    }
}
