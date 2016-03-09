<?php

namespace Progsmile\Validator\Rules;

class NotIn extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        $value = $this->getParams()[1];

        foreach (explode(',', $this->getParams()[2]) as $elem) {
            if ($value == trim($elem)) {
                return false;
            }
        }

        return true;
    }

    public function getMessage()
    {
        return 'Field :field: has wrong values';
    }
}
