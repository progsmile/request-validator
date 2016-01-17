<?php
namespace Progsmile\Validator\Rules;

use Progsmile\Validator\Contracts\Rules\RulesInterface;

class Same extends BaseRule implements RulesInterface
{
    public function isValid()
    {
        $fieldValue        = $this->params[1];
        $comparedFieldName = $this->params[2];
        $userData          = $this->getConfig(BaseRule::CONFIG_DATA);

        return $fieldValue == $userData[$comparedFieldName];
    }

    public function getMessage()
    {
        return 'Field :field: should have same value with :value: field';
    }
}
