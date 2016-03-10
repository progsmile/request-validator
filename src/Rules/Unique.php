<?php

namespace Progsmile\Validator\Rules;

class Unique extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()) {
            return true;
        }

        $config = $this->getConfig();

        $field = $this->getParams()[0];
        $value = $this->getParams()[1];
        $table = $this->getParams()[2];

        $instance = new $config[BaseRule::CONFIG_ORM]($field, $value, $table);

        return $instance->isUnique();
    }

    public function getMessage()
    {
        return 'Field :field: must be unique';
    }
}
