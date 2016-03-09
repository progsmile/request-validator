<?php

namespace Progsmile\Validator\Rules;

class Between extends BaseRule
{
    private $val1;
    private $val2;
    private $isNumeric = false;

    /**
     * {@inheritdoc}
     */
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        $values = explode(',', $this->getParams()[2]);

        $input = trim($this->getParams()[1]);

        $between = $this->respect('Between', [
            trim($values[0]), // min
            trim($values[1]), // max
            true              // inclusive
        ]);

        if ($this->hasRule('numeric') !== false) {
            $this->isNumeric = true;

            return $between->validate($input);
        }

        $input = mb_strlen($input);

        return $between->validate($input);
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
