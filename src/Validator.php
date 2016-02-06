<?php
namespace Progsmile\Validator;

use Progsmile\Validator\Helpers\ErrorBag,
    Progsmile\Validator\Helpers\PdoTrait,
    Progsmile\Validator\Helpers\RulesFactory,
    Progsmile\Validator\Rules\BaseRule;

final class Validator
{
    use PdoTrait;

    /** @var ErrorBag */
    private static $errorBag = null;


    private static $config = [
        BaseRule::CONFIG_ORM => '\Progsmile\Validator\DbProviders\PhalconORM',
    ];


    /**
     * Make validation
     *
     * @param array $data user request data
     * @param array $rules validation rules
     * @param array $userMessages custom error messages
     * @return ErrorBag
     */
    public static function make(array $data, array $rules, array $userMessages = [])
    {
        self::$errorBag = new ErrorBag($userMessages);

        $data  = self::prepareData($data);
        $rules = self::prepareRules($rules);

        foreach ($rules as $fieldName => $fieldRules) {

            $fieldName  = trim($fieldName);
            $fieldRules = trim($fieldRules);

            if ( !$fieldRules){
                //no rules
                continue;
            }

            $groupedRules = explode('|', $fieldRules);

            foreach ($groupedRules as $concreteRule) {

                $ruleNameParam = explode(':', $concreteRule);
                $ruleName      = $ruleNameParam[0];

                //for date/time validators
                if (count($ruleNameParam) >= 2){
                    $ruleValue = implode(':', array_slice($ruleNameParam, 1));

                    //for other params
                } else {
                    $ruleValue = isset($ruleNameParam[1]) ? $ruleNameParam[1] : '';
                }

                self::$config[BaseRule::CONFIG_DATA]        = $data;
                self::$config[BaseRule::CONFIG_FIELD_RULES] = $fieldRules;

                $ruleInstance = RulesFactory::createRule($ruleName, self::$config, [
                    $fieldName,                                        // The field name
                    isset($data[$fieldName]) ? $data[$fieldName] : '', // The provided value
                    $ruleValue,                                        // The rule's value
                ]);

                if ( !$ruleInstance->isValid()){

                    self::$errorBag->chooseErrorMessage($ruleInstance);
                }
            }
        }

        return self::$errorBag;
    }


    /**
     * Prepare user data for validator
     * @param array $data
     * @return array
     */
    private static function prepareData(array $data)
    {
        $newData = [];

        foreach ($data as $paramName => $paramValue) {

            if (is_array($paramValue)){

                foreach ($paramValue as $newKey => $newValue) {
                    $newData[trim($paramName) . '.' . trim($newKey)] = trim($newValue);
                }

            } else {

                $newData[trim($paramName)] = trim($paramValue);
            }
        }

        return $newData;
    }

    /**
     * Merges all field's rules into one
     * if you have elegant implementation, you are welcome
     * @param array $rules
     * @return array
     */
    private static function prepareRules(array $rules)
    {
        $mergedRules = [];

        foreach ($rules as $ruleFields => $ruleConditions) {

            //if set of fields like 'firstname, lastname...'
            if (strpos($ruleFields, ',') !== false){

                foreach (explode(',', $ruleFields) as $fieldName) {
                    $fieldName = trim($fieldName);

                    if ( !isset($mergedRules[$fieldName])){
                        $mergedRules[$fieldName] = $ruleConditions;
                    } else {
                        $mergedRules[$fieldName] .= '|' . $ruleConditions;
                    }
                }

            } else {

                if ( !isset($mergedRules[$ruleFields])){
                    $mergedRules[$ruleFields] = $ruleConditions;
                } else {
                    $mergedRules[$ruleFields] .= '|' . $ruleConditions;
                }
            }
        }

        $finalRules = [];

        //remove duplicated rules, like 'required|alpha|required'
        foreach ($mergedRules as $newRule => $rule) {
            $finalRules[$newRule] = implode('|', array_unique(explode('|', $rule)));
        }

        return $finalRules;
    }


    /**
     * Singleton implementation
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
