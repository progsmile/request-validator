<?php
namespace Progsmile\Validator\Rules;

use Progsmile\Validator\Contracts\Rules\RulesInterface;

class Max extends BaseRule implements RulesInterface
{
    private $params;

    public function isValid()
    {
        if ( strlen($this->params[1]) <= (int) $this->params[2] ) {

            return true;
        }

        return false;
    }

    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    public function getMessage()
    {
        return 'Field :field: should be maximum of :value: characters.';
    }
}
