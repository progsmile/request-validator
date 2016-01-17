<?php
namespace Progsmile\Validator\Rules;

use Progsmile\Validator\Contracts\Rules\RulesInterface;

class Boolean extends BaseRule implements RulesInterface
{
    public function isValid()
    {
        return is_bool($this->params[1]);
    }

    public function getMessage()
    {
        return 'Field :field: is not a boolean.';
    }
}
