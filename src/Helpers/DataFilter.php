<?php

namespace Progsmile\Validator\Helpers;

class DataFilter
{

    /**
     * @param array $data
     *
     * @return array
     */
    public static function prepareData(array $data)
    {
        $newData = [];

        foreach ($data as $paramName => $paramValue) {
            if (is_array($paramValue)) {
                foreach ($paramValue as $newKey => $newValue) {
                    $newData[trim($paramName).'['.trim($newKey).']'] = trim($newValue);
                }
            } else {
                $newData[trim($paramName)] = trim($paramValue);
            }
        }

        return $newData;
    }

    /**
     * @param array $rules
     * @return array
     *
     * @throws \Exception
     */
    public static function filterRules(array $rules)
    {
        $mergedRules = [];

        foreach ($rules as $ruleField => $ruleConditions) {
            $ruleConditions = self::parseRuleConditions($ruleConditions);

            if (self::hasMultipleFields($ruleField)) {
                foreach (explode(',', $ruleField) as $fieldName) {
                    $mergedRules = self::mergeFields($mergedRules, trim($fieldName), $ruleConditions);
                }

            } else {
                $mergedRules = self::mergeFields($mergedRules, $ruleField, $ruleConditions);
            }
        }

        return $mergedRules;
    }

    /**
     * @param $ruleConditions
     * @return array
     *
     * @throws \Exception
     */
    protected static function parseRuleConditions($ruleConditions)
    {
        if (is_string($ruleConditions)) {
            return explode('|', $ruleConditions);
        } elseif (is_array($ruleConditions)) {
            return $ruleConditions;
        }

        throw new \Exception("Rules should be rather string or array");
    }

    /**
     * @param string $ruleField
     * @return bool
     */
    protected static function hasMultipleFields($ruleField)
    {
        return strpos($ruleField, ',') !== false;
    }

    /**
     * @param array $mergeRules
     * @param string $fieldName
     * @param array $ruleConditions
     *
     * @return array
     */
    protected static function mergeFields(array $mergeRules, $fieldName, array $ruleConditions)
    {
        if (!isset($mergeRules[$fieldName])) {
            $mergeRules[$fieldName] = array_unique($ruleConditions);
        } else {
            $mergeRules[$fieldName] = array_unique(array_merge($mergeRules[$fieldName], $ruleConditions));
        }

        return $mergeRules;
    }
}
