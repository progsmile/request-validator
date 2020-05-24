<?php

namespace Progsmile\Validator\Helpers;

use Progsmile\Validator\Rules\BaseRule;

class RulesFactory
{
    /**
     * @param string $rule
     * @param $config
     * @param $params
     *
     * @return BaseRule
     */
    public static function createRule($rule, $config, $params)
    {

        // path to custom class
        if (class_exists($rule)) {
            $ruleInstance = new $rule($config);
            $ruleInstance->setParams($params);

            return $ruleInstance;
        }

        $rule = ucfirst($rule);

        if (!file_exists(__DIR__ . '/../Rules/' . $rule . '.php')) {
            throw new \Exception('Rule doesn\'t exists: ' . $rule);
        }

        $class = 'Progsmile\\Validator\\Rules\\' . $rule;

        $ruleInstance = new $class($config);
        $ruleInstance->setParams($params);

        return $ruleInstance;
    }
}
