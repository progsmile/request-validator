<?php
namespace Progsmile\Validator\Rules;

use Progsmile\Validator\Contracts\Rules\RulesInterface;

class Json extends BaseRule implements RulesInterface
{
    public function isValid()
    {
        if ($this->isNotRequired()) {
            return true;
        }

        json_decode($this->params[1]);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    public function getMessage()
    {
        return 'Field :field: has not json format.';
    }
}
