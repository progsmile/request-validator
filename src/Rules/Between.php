<?php

namespace Progsmile\Validator\Rules;

class Between extends BaseRule
{
    private $val1;
    private $val2;
    private $isNumeric = false;

    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        $validatorValues = explode(',', $this->params[2]);

        $this->val1 = trim($validatorValues[0]);
        $this->val2 = trim($validatorValues[1]);
        $input = trim($this->params[1]);

        if ($this->hasRule('numeric') !== false) {
            $this->isNumeric = true;

            return $this->val1 <= $input && $input <= $this->val2;
        }

        $input = mb_strlen($input);

        return $this->val1 <= $input && $input <= $this->val2;
    }

    /**
     * Returns error message from rule.
     *
     * @return string
     */
    public function getMessage()
    {
        if ($this->isNumeric) {
            return 'Field :field: should be between in :param: values';
        }

        return 'Field :field: length should be between :param: characters';
    }
}
