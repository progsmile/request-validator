<?php

namespace Progsmile\Validator\Rules;

class Required extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        $value = trim($this->getParams()[1]);

        return (bool) $value || $value == '0'
            || !empty($_FILES) && isset($_FILES[$this->getParams()[0]]) && $_FILES[$this->getParams()[0]]['name'];
    }

    public function getMessage()
    {
        return 'Field :field: is required';
    }
}
