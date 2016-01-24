<?php
namespace Progsmile\Validator\Rules;

class Accepted extends BaseRule
{
    public function isValid()
    {
        $value = isset($this->params[1]) ? $this->params[1] : false;

        return in_array($value, ['yes', 'on', 1, true] );
    }

    public function getMessage()
    {
        return 'Field :field: should be accepted.';
    }
}
