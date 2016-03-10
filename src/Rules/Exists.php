<?php

namespace Progsmile\Validator\Rules;

class Exists extends BaseRule
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

        return $instance->isExist();
    }

    /**
     * Returns error message from rule.
     *
     * @return string
     */
    public function getMessage()
    {
        return 'Field :field: doesn\'t exist in table';
    }
}
