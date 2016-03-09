<?php

namespace Progsmile\Validator\Rules;

class Same extends BaseRule
{
    public function isValid()
    {
        $data = $this->getConfig(BaseRule::CONFIG_DATA);

        $input = $this->getParams()[1];
        $value = $this->getParams()[2];

        if (!isset($data[$value])) {
            return false;
        }

        return $this->respect('Equals', [$data[$value]])->validate($input);
    }

    public function getMessage()
    {
        return 'Field :field: should have same value with :value: field';
    }
}
