<?php
namespace Progsmile\Validator\Rules;

class Required extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        $value = trim($this->params[1]);

        return (bool)$value or $value == '0' || $this->hasRule('image') && isset($_FILES[$this->params[0]])
                                        && $_FILES[$this->params[0]]['name'];
    }

    public function getMessage()
    {
        return 'Field :field: is required';
    }
}
