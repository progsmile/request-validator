<?php
namespace Progsmile\Validator\Rules;

use Progsmile\Validator\Contracts\Rules\RulesInterface;

class Unique extends BaseRule implements RulesInterface
{
    private $params;

    public function isValid()
    {
        $config = $this->getConfig();

        $field = $this->params[0];
        $value = $this->params[1];
        $table = $this->params[2];

        $instance = new $config['orm']($field, $value, $table);

        return $instance->isUnique();
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function getMessage()
    {
        return 'Field :field: must be unique.';
    }
}
