<?php
namespace Progsmile\Validator\Rules;

use Progsmile\Validator\Contracts\Rules\RulesInterface;

class Accepted extends BaseRule implements RulesInterface
{
    public function isValid()
    {
        return isset($this->params[1]) && $this->params[1];
    }

    public function getMessage()
    {
        return 'Field :field: should be accepted.';
    }
}
