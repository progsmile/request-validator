<?php
namespace Progsmile\Validator\Rules;

class Required implements RulesInterface
{
    private $params;

    public function fire()
    {
        return (bool) $this->params[0];
    }

    public function setParams($params)
    {
        $this->params = $params;
    }
}
