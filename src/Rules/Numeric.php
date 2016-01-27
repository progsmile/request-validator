<?php
namespace Progsmile\Validator\Rules;

class Numeric extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        return is_numeric($this->params[1]);
    }
}
