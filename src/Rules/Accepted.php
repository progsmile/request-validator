<?php
namespace Progsmile\Validator\Rules;

class Accepted extends BaseRule
{
    public function isValid()
    {
        return isset($this->params[1]) && $this->params[1];
    }

    public function getMessage()
    {
        return 'Field :field: should be accepted.';
    }
}
