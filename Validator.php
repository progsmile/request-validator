<?php
namespace Progsmile\Validator;

use Progsmile\Validator\Format\HTML as FormatHTML;

class Validator
{
    const MAP = [
        'required' => 'Field :first: is required.',
        'email'    => 'Field :first: has a bad email format.',
        'min'      => 'Field :first: should be minimum :second:.',
        'max'      => 'Field :first: should be maximum :second:.',
        'email'    => 'Field :first: has a bad email format.',
    ];

    private $isValid = true;
    private $errorMessages = [];

    public function make($data, $rules, $userMessages = [])
    {
        foreach ($rules as $fieldName => $fieldRules) {

            $groupedRules = explode('|', $fieldRules);

            foreach ($groupedRules as $concreteRule) {

                $ruleNameParam = explode(':', $concreteRule);
                $ruleName      = $ruleNameParam[0];
                $ruleParam     = isset($ruleNameParam[1]) ? $ruleNameParam[1] : '';

                $class = __NAMESPACE__.'\\Rules\\'.ucfirst($ruleName);

                $instance = new $class;
                $instance->setParams([
                    $data[$fieldName],
                    $ruleParam,
                ]);

                $this->isValid = $instance->fire();

                if ( $this->isValid == false ) {

                    $ruleErrorFormat = $fieldName.'.'.$ruleName;

                    if ( isset($userMessages[$ruleErrorFormat]) ) {

                        $this->errorMessages[$fieldName][] = $userMessages[$ruleErrorFormat];

                    } else {

                        $message = self::MAP[$ruleName];

                        $message = strtr(
                            $message,
                            [
                                ':first:'  => $fieldName,
                                ':second:' => $ruleParam,
                            ]
                        );

                        $this->errorMessages[$fieldName][] = $message;
                    }
                }

                return $this;
            }

        }
    }

    public function isValid()
    {
        if ( $this->isValid ) {

            return true;
        }

        return false;
    }

    public function messages()
    {
        return $this->errorMessages;
    }

    public function format($class = FormatHTML::class)
    {
        return (new $class)->reformat($this->errorMessages);
    }
}
