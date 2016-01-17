<?php
namespace Progsmile\Validator\Rules;

use Progsmile\Validator\Contracts\Rules\RulesInterface;

class Required extends BaseRule implements RulesInterface
{
    public function isValid()
    {
        if ($this->isNotRequired()) {
            return true;
        }

        return (bool) $this->params[1];
    }

    public function getMessage()
    {
        return 'Field :field: is required';
    }
}
