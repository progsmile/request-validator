<?php
namespace Progsmile\Validator\Rules;

use Progsmile\Validator\Contracts\Rules\RulesInterface;

class Alpha extends BaseRule implements RulesInterface
{
    private $params;

    public function isValid()
    {
        return is_string($this->params[1]) && preg_match('/^[\pL\pM]+$/u', $this->params[1]);
    }

    //@todo: some architect variants
    //we may create abstract class, just not implementing this function from class to class
    //and create abstract `fire` method, what do you think?

    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    public function getMessage()
    {
        return 'Field :field: must be in alpha numeric format.';
    }
}