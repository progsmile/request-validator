<?php
namespace Progsmile\Validator\Rules;

class Between extends BaseRule
{
    private $val1;
    private $val2;

    public function isValid()
    {
        if ($this->isNotRequired()){
            return true;
        }

        $validatorValues = explode(',', $this->params[2]);
        $userValue       = $this->params[1];

        $this->val1 = (isset($validatorValues[0]) && is_numeric($validatorValues[0])) ? floatval($validatorValues[0]) : 0;
        $this->val2 = (isset($validatorValues[1]) && is_numeric($validatorValues[1])) ? floatval($validatorValues[1]) : 0;

        return $this->val1 <= $userValue && $userValue <= $this->val2;
    }

    public function getMessage()
    {
        return 'Field :field:\'s value is not between ' . $this->val1 . ' and ' . $this->val2;
    }
}
