<?php
namespace Progsmile\Validator;

use Progsmile\Validator\Rules\BaseRule;

final class Validator
{
    private static $validatorInstance = null;

    private static $pdoInstance = null;

    private static $errorMessages = [];

    private static $config = [
        'orm' => '\Progsmile\Validator\DbProviders\PhalconORM',
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
            self::$validatorInstance = new Validator();
        }

        self::$errorMessages = [];

        foreach ($rules as $groupedFieldNames => $fieldRules) {

            $fieldRules = trim($fieldRules);

            if ( !$fieldRules){
                //no rules
                continue;
            }

            //for fields separated with comma
            $fieldNames = explode(',', $groupedFieldNames);

            foreach ($fieldNames as $fieldName) {

                $fieldName = trim($fieldName);

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

                            self::$errorMessages[$fieldName][] = $userMessages[$ruleErrorFormat];

                        } else {

                            self::$errorMessages[$fieldName][] = strtr($instance->getMessage(), [
                                    ':field:' => $fieldName,
                                    ':value:' => $ruleValue,
                                ]
                            );
                        }
                    }
                }
            }
        }

        return self::$validatorInstance;
    }

    public function isValid()
    {
        return count(self::$errorMessages) == 0;
    }

    public function getMessages($field = '')
    {
        //get messages for specific field
        if ($field){
            return isset(self::$errorMessages[$field]) ? self::$errorMessages[$field] : [];
        }

        //return plain messages array
        $messages = [];

        array_walk_recursive(self::$errorMessages, function ($message) use (&$messages) {
            $messages[] = $message;
        });

        return $messages;
    }

    public function getFirstMessage($field = '')
    {
        return isset(self::$errorMessages[$field]) ? reset(self::$errorMessages[$field]) : '';
    }

    public function format($class = 'Progsmile\Validator\Format\HTML')
    {
        return (new $class)->reformat(self::$errorMessages);
    }

    //singleton
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
