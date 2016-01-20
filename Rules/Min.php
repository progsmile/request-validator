<?php
namespace Progsmile\Validator\Rules;

class Min extends BaseRule
{
    private $isNumeric = false;

    public function isValid()
    {
        if ($this->isNotRequired()) {
            return true;
        }

        //if `numeric` rule founds - validate as a number
        if ( $this->hasRule('numeric') !== false ) {

            $this->isNumeric = true;

            return $this->params[1] >= $this->params[2];
        }

        return is_string($this->params[1]) && strlen($this->params[1]) >= $this->params[2];
    }

    public function getMessage()
    {
        if ($this->isNumeric) {

            $message = 'Field :field: should be grater than :value:.';

        } else {

            $message = 'Field :field: should be at least :value: characters.';
        }

        return $message;
    }
}
