<?php
namespace Progsmile\Validator\Rules;


use Progsmile\Validator\Contracts\Rules\RulesInterface;

class Required extends BaseRule implements RulesInterface
{
    private $params;

    public function isValid()
    {
        return (bool) $this->params[1];
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function getMessage()
    {
        return 'Field :field: is required';
    }
}
