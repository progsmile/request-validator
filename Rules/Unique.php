<?php
namespace Progsmile\Validator\Rules;

use Progsmile\Validator\Contracts\Rules\RulesInterface;

class Unique extends BaseRule implements RulesInterface
{
    public function isValid()
    {
        if ($this->isNotRequired()) {
            return true;
        }

        $config = $this->getConfig();

        $field = $this->params[0];
        $value = $this->params[1];
        $table = $this->params[2];

        $instance = new $config[BaseRule::CONFIG_ORM]($field, $value, $table);

        return $instance->isUnique();
    }

    public function getMessage()
    {
        return 'Field :field: must be unique.';
    }
}
