<?php
namespace Progsmile\Validator\Rules;

class Between extends BaseRule
{
    private $val1;
    private $val2;

    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()){
            return true;
        }

        $validatorValues = explode(',', $this->params[2]);

        $this->val1 = trim($validatorValues[0]);
        $this->val2 = trim($validatorValues[1]);
        $userValue  = trim($this->params[1]);

        return is_numeric($userValue) && $this->val1 <= $userValue && $userValue <= $this->val2;
    }
}
