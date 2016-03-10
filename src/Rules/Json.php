<?php

namespace Progsmile\Validator\Rules;

class Json extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        return $this->respect('Json')->validate($this->getParams()[1]);
    }

    public function getMessage()
    {
        return 'Field :field: is not json';
    }
}
