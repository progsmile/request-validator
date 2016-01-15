<?php
namespace Progsmile\Validator\Rules;

use Progsmile\Validator\Contracts\Rules\RulesInterface;

class Accepted extends BaseRule implements RulesInterface
{
    private $params;

    public function fire()
    {
        return isset($this->params[1]); // && in_array($this->params[1], ['yes', 'on', 1, true]);
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function getMessage()
    {
        return 'Field :field: should be accepted.';
    }
}