<?php
namespace Progsmile\Validator\Rules;

use Progsmile\Validator\Contracts\Rules\RulesInterface;

class Accepted extends BaseRule implements RulesInterface
{
    private $params;

    public function isValid()
    {
        return isset($this->params[1]) && $this->params[1];
    }

    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    public function getMessage()
    {
        return 'Field :field: should be accepted.';
    }
}