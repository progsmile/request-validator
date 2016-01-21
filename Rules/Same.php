<?php
namespace Progsmile\Validator\Rules;

class Same extends BaseRule
{
    public function isValid()
    {
        $fieldValue        = $this->params[1];
        $comparedFieldName = $this->params[2];
        $userData          = $this->getConfig(BaseRule::CONFIG_DATA);

        return isset($userData[$comparedFieldName]) && $fieldValue == $userData[$comparedFieldName];
    }

    public function getMessage()
    {
        return 'Field :field: should have same value with :value: field';
    }
}
