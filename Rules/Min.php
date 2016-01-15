<?php
namespace Progsmile\Validator\Rules;

use Progsmile\Validator\Contracts\Rules\RulesInterface;

class Min extends BaseRule implements RulesInterface
{
    private $params;

    public function isValid()
    {
        if ( $this->params[2] <= strlen($this->params[1]) ) {

            return true;
        }

        return false;
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function getMessage()
    {
        return 'Field :field: should be atleast :value: characters.';
    }
}
