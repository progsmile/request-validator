<?php
namespace Progsmile\Validator\Rules;

use Progsmile\Validator\Contracts\Rules\RulesInterface;

class Same extends BaseRule implements RulesInterface
{
    private $params;

    public function isValid()
    {
        //
    }

    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    public function getMessage()
    {
        return 'Field :field: should have same value with :value: field';
    }
}