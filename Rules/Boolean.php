<?php
namespace Progsmile\Validator\Rules;

use Progsmile\Validator\Contracts\Rules\RulesInterface;

class Boolean extends BaseRule implements RulesInterface
{
    private $params;

    public function fire()
    {
        return is_bool($this->params[1]);
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function getMessage()
    {
        return 'Field :field: is not a boolean.';
    }
}