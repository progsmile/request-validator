<?php
namespace Progsmile\Validator\Rules;

class Required extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequired()) {
            return true;
        }

        return (bool) $this->params[1] || $this->hasRule('image') && isset($_FILES[$this->params[0]])
                                        && $_FILES[$this->params[0]]['name'];
    }

    public function getMessage()
    {
        return 'Field :field: is required';
    }
}
