<?php
namespace Progsmile\Validator\Rules;

class Size extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        $value = trim($this->params[1]);

        return mb_strlen($value) == $this->params[2];
    }

    /**
     * Returns error message from rule
     *
     * @return string
     */
    public function getMessage()
    {
        return 'Field :field: should has exact size :param:';
    }
}
