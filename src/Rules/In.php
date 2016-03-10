<?php

namespace Progsmile\Validator\Rules;

class In extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        $input = trim($this->getParams()[1]);

        $values = array_map(function ($elem) {
            return trim($elem);
        }, explode(',', $this->getParams()[2]));

        return $this->respect('In', [$values])->validate($input);
    }

    public function getMessage()
    {
        return 'Field :field: has wrong values';
    }
}
