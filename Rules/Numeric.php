<?php
namespace Progsmile\Validator\Rules;

use Progsmile\Validator\Contracts\Rules\RulesInterface;

class Numeric extends BaseRule implements RulesInterface
{
    private $params;

    public function isValid()
    {
        return is_numeric($this->params[1]);
    }

    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    public function getMessage()
    {
        return 'Field :field: is not a number.';
    }
}
