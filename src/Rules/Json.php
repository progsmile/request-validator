<?php
namespace Progsmile\Validator\Rules;

class Json extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        json_decode($this->params[1]);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    public function getMessage()
    {
        return 'Field :field: is not json';
    }
}
