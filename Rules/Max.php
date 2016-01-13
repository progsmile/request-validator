<?php
namespace Progsmile\Validator\Rules;

class Max implements RulesInterface
{
    private $params;

    public function fire()
    {
        return $this->params[0] <= $this->params[1];
    }

    public function setParams($params)
    {
        $this->params = $params;
    }
}
