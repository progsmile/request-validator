<?php
namespace Progsmile\Validator\Rules;

class In extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()){
            return true;
        }

        $value = $this->params[1];

        foreach (explode(',', $this->params[2]) as $elem) {
            if ($value == trim($elem)){
                return true;
            }
        }

        return false;
    }

    public function getMessage()
    {
        return 'Field :field: has wrong values';
    }
}