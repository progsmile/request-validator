<?php
namespace Progsmile\Validator\Rules;

class Email implements RulesInterface
{
    private $params;

    public function fire()
    {
        return (bool) filter_var($this->params[0], FILTER_VALIDATE_EMAIL);
    }

    public function setParams($params)
    {
        $this->params = $params;
    }
}
