<?php
namespace Progsmile\Validator;

use Progsmile\Validator\Contracts\Frameworks\OrmInterface;
use Progsmile\Validator\Format\HTML as FormatHTML;
use Progsmile\Validator\Rules\BaseRule;

class Validator
{
    private static $config = [
        'orm' => \Progsmile\Validator\DbProviders\PhalconORM::class,
    ];

    private $classes = [];
    private static $errorMessages = [];


    private function __construct()
    {
    }

    public static function setDbProvider($orm)
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
    public static function make($data, $rules, $userMessages = [])
    {
        return (new static())->validate($data, $rules, $userMessages);
    }

    private function validate($data, $rules, $userMessages = [])
    {
        self::$errorMessages = [];

        foreach ($rules as $fieldName => $fieldRules) {

            $fieldRules = trim($fieldRules);

            if ( !$fieldRules){
                //no rules
                continue;
            }

            $groupedRules = explode('|', $fieldRules);

            foreach ($groupedRules as $concreteRule) {

                $ruleNameParam = explode(':', $concreteRule);
                $ruleName      = $ruleNameParam[0];
                $ruleValue     = isset($ruleNameParam[1]) ? $ruleNameParam[1] : '';

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

        return $this;
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

    public function format($class = FormatHTML::class)
    {
        return (new $class)->reformat(self::$errorMessages);
    }

    public function configure($config)
    {
        self::$config = array_merge(self::$config, $config);

        return $this;
    }

    public function injectClass(BaseRule $class)
    {
        if ( !$class instanceof BaseRule){
            throw new \Exception('Class should be instance of BaseRule');
        }

        $this->classes[] = $class;

        return $this;
    }
}
