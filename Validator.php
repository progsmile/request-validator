<?php
namespace Progsmile\Validator;

use Progsmile\Validator\Format\HTML as FormatHTML;

class Validator
{
    private $config = [
        'orm' => \Progsmile\Validator\Frameworks\Phalcon\ORM::class,
    ];

    private $class = null;
    private $isValid = true;
    private $errorMessages = [];
    private $userClassNotUsed = false;

    public function __construct($class = null)
    {
        $this->class = $class;
    }

    public function make($data, $rules, $userMessages = [])
    {
        foreach ($rules as $fieldName => $fieldRules) {

            $groupedRules = explode('|', $fieldRules);

            foreach ($groupedRules as $concreteRule) {

                $ruleNameParam = explode(':', $concreteRule);
                $ruleName      = $ruleNameParam[0];
                $ruleValue     = isset($ruleNameParam[1]) ? $ruleNameParam[1] : '';

                $class = __NAMESPACE__ . '\\Rules\\' . ucfirst($ruleName);

                if ($this->class && !$this->userClassNotUsed) {

                    $class = $this->class;

                    $this->userClassNotUsed = true;
                }

                $instance = new $class($this->config);

                $instance->setParams([
                    $fieldName,                                        // The field name
                    isset($data[$fieldName]) ? $data[$fieldName] : '', // The provided value
                    $ruleValue,                                        // The rule's value
                ]);

                $this->isValid = $instance->isValid();

                if ($this->isValid == false) {

                    $ruleErrorFormat = $fieldName . '.' . $ruleName;

                    if (isset($userMessages[$ruleErrorFormat])) {

                        $this->errorMessages[$fieldName][] = $userMessages[$ruleErrorFormat];

                    } else {

                        $message = $instance->getMessage();

                        $message = strtr(
                            $message,
                            [
                                ':field:' => $fieldName,
                                ':value:' => $ruleValue,
                            ]
                        );

                        $this->errorMessages[$fieldName][] = $message;
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

    public function messages()
    {
        return $this->errorMessages;
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

    public function injectClass($class)
    {
        $this->class = $class;

        return $this;
    }
}