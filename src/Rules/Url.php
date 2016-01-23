<?php
namespace Progsmile\Validator\Rules;

class Url extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequired()) {
            return true;
        }

        return filter_var($this->params[1], FILTER_VALIDATE_URL) !== false;
    }

    public function getMessage()
    {
        return 'Field :field: is not URL.';
    }
}