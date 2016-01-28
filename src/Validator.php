<?php
namespace Progsmile\Validator;

use Progsmile\Validator\Helpers\ErrorBag;
use Progsmile\Validator\Rules\BaseRule;

final class Validator
{
    /** @var Validator */
    private static $validatorInstance = null;

    /** @var ErrorBag */
    private static $errorBag = null;

    private static $pdoInstance = null;

    private static $config = [
        BaseRule::CONFIG_ORM => '\Progsmile\Validator\DbProviders\PhalconORM',
    ];


    /**
     * Initialize PDO connection
     *
     * @param string $connectionString - ex. (mysql:host=localhost;dbname=test)
     * @param string $user - db username
     * @param string $password - db password
     */
    public static function setupPDO($connectionString, $user, $password)
    {
        try {
            self::$pdoInstance = new \PDO($connectionString, $user, $password);
        } catch (\PDOException $e) {
            trigger_error($e->getMessage(), E_USER_ERROR);
        }
    }

    /**
     * Setup PDO instance
     * @param \PDO $pdoInstance
     */
    public static function setPDO(\PDO $pdoInstance)
    {
        self::$pdoInstance = $pdoInstance;
    }

    /**
     * Get PDO object for unique validators
     *
     * @return mixed
     */
    public static function getPDO()
    {
        return self::$pdoInstance ?: null;
    }

    /**
     * Setup database service from available
     * @param $orm
     */
    public static function setDataProvider($orm)
    {
        self::$config[BaseRule::CONFIG_ORM] = $orm;
    }

    /**
     * Make validation
     *
     * @param array $data user request data
     * @param array $rules validation rules
     * @param array $userMessages custom error messages
     * @return Validator
     */
    public static function make(array $data, array $rules, array $userMessages = [])
    {
        if (self::$validatorInstance === null){

            self::$errorBag = new ErrorBag();

            self::$validatorInstance = new Validator();
        }


        $rules = self::prepareRules($rules);

        self::$errorBag->clear();

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


                $class = __NAMESPACE__ . '\\Rules\\' . ucfirst($ruleName);

                self::$config[BaseRule::CONFIG_DATA]        = $data;
                self::$config[BaseRule::CONFIG_FIELD_RULES] = $fieldRules;

                /** @var BaseRule $instance */
                $instance = new $class(self::$config);

                $instance->setParams([
                    $fieldName,                                        // The field name
                    isset($data[$fieldName]) ? $data[$fieldName] : '', // The provided value
                    $ruleValue,                                        // The rule's value
                ]);

                if ( !$instance->isValid()){

                    $ruleErrorFormat = $fieldName . '.' . $ruleName;

                    if (isset($userMessages[$ruleErrorFormat])){
                        $message = $userMessages[$ruleErrorFormat];

                    } else {
                        $message = strtr($instance->getMessage(), [
                                ':field:' => $fieldName,
                                ':value:' => $ruleValue,
                            ]
                        );
                    }

                    self::$errorBag->addMessage($fieldName, $message);
                }
            }
        }

        return self::$validatorInstance;
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
     * Checks request is valid
     * @return bool
     */
    public function isValid()
    {
        return self::$errorBag->getMessagesCount() === 0;
    }

    /**
     * Returns all error messages | Or all error messages from concrete field
     * @param string $field
     * @return array
     */
    public function getMessages($field = '')
    {
        return self::$errorBag->getMessages($field);
    }


    /**
     * Returns first error message from each fields
     * @return array
     */
    public function getFirstMessages()
    {
        return self::$errorBag->getFirstMessages();
    }

    /**
     * Returns first error message from concrete field or from validation stack
     * @param string $field
     * @return mixed|string
     */
    public function getFirstMessage($field = '')
    {
        return self::$errorBag->getFirstMessage($field);
    }

    /**
     * Setup custom error messages
     *
     * @param $rule
     * @param $message
     */
    public static function setDefaultMessage($rule, $message)
    {
        self::$errorBag->setDefaultMessage($rule, $message);
    }

    /**
     * Returns custom message by rule
     * @param $ruleClassName
     * @return mixed
     */
    public static function getDefaultMessage($ruleClassName)
    {
        return self::$errorBag->getDefaultMessage($ruleClassName);
    }

    /**
     * Reformat messages provided by HTML, JSON or custom FormatInterface classes
     * @param string $class
     * @return mixed
     */
    public function format($class = 'Progsmile\Validator\Format\HTML')
    {
        return (new $class)->reformat(self::$errorBag->getRawMessages());
    }


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
