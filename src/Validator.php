<?php

namespace Progsmile\Validator;

use Progsmile\Validator\Helpers\DataFilter;
use Progsmile\Validator\Helpers\RulesFactory;
use Progsmile\Validator\Helpers\ValidatorFacade;
use Progsmile\Validator\Rules\BaseRule;

final class Validator
{
    /** @var ValidatorFacade */
    private static $validatorFacade = null;

    private static $config = [];

    /**
     * Make validation.
     *
     * @param array $data         user request data
     * @param array $rules        validation rules
     * @param array $userMessages custom error messages
     *
     * @return ValidatorFacade
     */
    public static function make(array $data, array $rules, array $userMessages = [])
    {
        self::$validatorFacade = new ValidatorFacade($userMessages);

        $data = DataFilter::prepareData($data);
        $rules = DataFilter::filterRules($rules);

        foreach ($rules as $fieldName => $fieldRules) {
            if (empty($fieldRules)) {
                //no rules
                continue;
            }

            foreach ($fieldRules as $concreteRule) {
                $ruleNameParam = explode(':', $concreteRule);
                $ruleName = $ruleNameParam[0];

                //for date/time validators
                if (count($ruleNameParam) >= 2) {
                    $ruleValue = implode(':', array_slice($ruleNameParam, 1));

                    //for other params
                } else {
                    $ruleValue = isset($ruleNameParam[1]) ? $ruleNameParam[1] : '';
                }

                self::$config[BaseRule::CONFIG_DATA] = $data;
                self::$config[BaseRule::CONFIG_FIELD_RULES] = $fieldRules;

                $ruleInstance = RulesFactory::createRule($ruleName, self::$config, [
                    $fieldName,                                        // The field name
                    isset($data[$fieldName]) ? $data[$fieldName] : '', // The provided value
                    $ruleValue,                                        // The rule's value
                ]);

                if (!$ruleInstance->isValid()) {
                    self::$validatorFacade->chooseErrorMessage($ruleInstance);
                }
            }
        }

        return self::$validatorFacade;
    }

    /**
     * Singleton
     */
    private function __construct()
    {
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }
}
