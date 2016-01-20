<?php
namespace Progsmile\Validator\Rules;

class Boolean extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequired()) {
            return true;
        }

        return is_bool($this->params[1]);
    }

    public function getMessage()
    {
        return 'Field :field: is not a boolean.';
    }
}
