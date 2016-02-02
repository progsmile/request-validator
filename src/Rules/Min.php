<?php
namespace Progsmile\Validator\Rules;

class Min extends BaseRule
{
    private $isNumeric = false;

    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
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
        if($this->isNumeric){
            return 'Field :field: should be grater than :param:';
        }

        return 'Field :field: should be at least :param: characters';
    }
}
