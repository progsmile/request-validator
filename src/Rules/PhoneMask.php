<?php
namespace Progsmile\Validator\Rules;

class PhoneMask extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequired()){
            return true;
        }

        $phone     = trim($this->params[1]);
        $phoneMask = substr($this->params[2], 1, -1);

        if(strlen($phone) != strlen($phoneMask)){
            return false;
        }

        foreach (str_split($phoneMask) as $index => $maskChar) {
            if ($maskChar == '#' && !is_numeric($phone[$index])){
                return false;
            }
        }

        return true;
    }

    public function getMessage()
    {
        return 'Field :field: has bad phone format.';
    }
}
