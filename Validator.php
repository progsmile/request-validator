<?php
namespace Progsmile\Validator;

use Progsmile\Validator\Format\HTML as FormatHTML;
use Progsmile\Validator\Rules\BaseRule;

class Validator
{
    private $config = [
        'orm' => \Progsmile\Validator\Frameworks\Phalcon\ORM::class,
    ];

    private $classes = [];
    private $isValid = true;
    private $errorMessages = [];


    private function __construct()
    {
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
        $this->errorMessages = [];

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

                $this->config[BaseRule::CONFIG_DATA]        = $data;
                $this->config[BaseRule::CONFIG_FIELD_RULES] = $fieldRules;

//                if ($this->classes) {
//
//                    $class = $this->classes;
//                }


                /** @var BaseRule $instance */
                $instance = new $class($this->config);

                $instance->setParams([
                    $fieldName,                                        // The field name
                    isset($data[$fieldName]) ? $data[$fieldName] : '', // The provided value
                    $ruleValue,                                        // The rule's value
                ]);

                $this->isValid = $instance->isValid();

                if ($this->isValid == false){

                    $ruleErrorFormat = $fieldName . '.' . $ruleName;

                    if (isset($userMessages[$ruleErrorFormat])){

                        $this->errorMessages[$fieldName][] = $userMessages[$ruleErrorFormat];

                    } else {

                        $this->errorMessages[$fieldName][] = strtr($instance->getMessage(), [
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
        return count($this->errorMessages) == 0;
    }

    public function getMessages($field = '')
    {
        //get messages for specific field
        if ($field){
            return isset($this->errorMessages[$field]) ? $this->errorMessages[$field] : [];
        }

        //return plain messages array
        $messages = [];

        array_walk_recursive($this->errorMessages, function ($message) use (&$messages) {
            $messages[] = $message;
        });

        return $messages;
    }

    public function format($class = FormatHTML::class)
    {
        return (new $class)->reformat($this->errorMessages);
    }

    public function configure($config)
    {
        $this->config = array_merge($this->config, $config);

        return $this;
    }

    public function injectClass(BaseRule $class)
    {
        if(!$class instanceof BaseRule){
            throw new \Exception('Class should be instance of BaseRule');
        }

        $this->classes[] = $class;

        return $this;
    }
}