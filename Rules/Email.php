<?php
namespace Progsmile\Validator\Rules;

use Progsmile\Validator\Contracts\Rules\RulesInterface;

class Email extends BaseRule implements RulesInterface
{
    private $params;

    public function isValid()
    {
        return (bool) filter_var($this->params[1], FILTER_VALIDATE_EMAIL);
    }

    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    public function getMessage()
    {
        return 'Field :field: has a bad email format.';
    }
}
