<?php
namespace Progsmile\Validator\Rules;

use Progsmile\Validator\Contracts\Rules\RulesInterface;

class Url extends BaseRule implements RulesInterface
{
    private $params;

    public function fire()
    {
        return filter_var($this->params[1], FILTER_VALIDATE_URL) === false;
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function getMessage()
    {
        return 'Field :field: is not URL.';
    }
}