<?php
namespace Progsmile\Validator\Rules;

class Min implements RulesInterface
{
    private $params;

    public function fire()
    {
        return strlen($this->params[0]) >= $this->params[1];
    }

    public function setParams($params)
    {
        $this->params = $params;
    }
}
