<?php

namespace Progsmile\Validator\Rules;

use Respect\Validation\Factory;

abstract class BaseRule
{
    const CONFIG_ALL = 'all';
    const CONFIG_DATA = 'data';
    const CONFIG_FIELD_RULES = 'fieldRules';

    private $config;
    private $params;
    private $respect;

    /**
     * BaseRule constructor.
     *
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->respect = new Factory();
    }

    /**
     * Returns all config array, or specific one.
     *
     * @param string $type
     *
     * @return array
     */
    public function getConfig($type = self::CONFIG_ALL)
    {
        if ($type == self::CONFIG_ALL) {
            return $this->config;
        }

        return isset($this->config[$type]) ? $this->config[$type] : [];
    }

    /**
     * If field has specific rule.
     *
     * @param $rule
     *
     * @return bool
     */
    public function hasRule($rule)
    {
        return in_array($rule, $this->getConfig(self::CONFIG_FIELD_RULES), true);
    }

    /**
     * Check if variable is not required - to prevent error messages from another validators.
     *
     * @param string $type | 'var' or 'file'
     *
     * @return bool
     */
    protected function isNotRequiredAndEmpty($type = 'var')
    {
        $condition = false;

        if ($type == 'var') {
            $condition = strlen($this->params[1]) == 0;
        } elseif ($type == 'file') {
            $fieldsName = $this->params[0];

            //when file field is not required and empty
            $condition = isset($_FILES[$fieldsName]['name']) && $_FILES[$fieldsName]['name'] == '';
        }

        return !$this->hasRule('required') && $condition;
    }

    /**
     * Set params to validator.
     *
     * @param $params
     *
     * @return $this
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Returns params.
     *
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Returns pure class name.
     *
     * @return string
     */
    public function getRuleName()
    {
        $classPath = explode('\\', get_class($this));

        return array_pop($classPath);
    }

    /**
     * Get the instantiated respect/validator factory.
     *
     * @return mixed
     */
    public function getRespectValidator()
    {
        return $this->respect;
    }

    protected function respect($ruleName, $arguments = [])
    {
        return $this->respect->rule($ruleName, $arguments);
    }

    /**
     * Returns error message from rule.
     *
     * @return string
     */
    abstract public function getMessage();

    /**
     * The main function that validates the inputted value against
     * an existing one or similar.
     *
     * @return bool Return a if values were valid/matched or not
     */
    abstract public function isValid();
}
