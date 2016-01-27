<?php
namespace Progsmile\Validator\Rules;

class Boolean extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        $userValue = trim($this->params[1]);

        return in_array($userValue, ['true', 'false']);
    }

    public function getMessage()
    {
        return 'Field :field: is not a boolean.';
    }
}
