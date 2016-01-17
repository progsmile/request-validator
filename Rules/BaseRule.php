<?php
namespace Progsmile\Validator\Rules;

class BaseRule
{
    const CONFIG_ALL         = 'all';
    const CONFIG_DATA        = 'data';
    const CONFIG_ORM         = 'orm';
    const CONFIG_FIELD_RULES = 'fieldRules';

    private $config;

    protected $params;

    public function __construct($config)
    {
        $this->config = $config;
    }

    protected function getConfig($type = self::CONFIG_ALL)
    {
        if ($type == self::CONFIG_ALL) {
            return $this->config;
        }

        return isset($this->config[$type]) ? $this->config[$type] : [];
    }

    protected function hasRule($rule = 'required')
    {
        return strpos($this->getConfig(self::CONFIG_FIELD_RULES), $rule) !== false;
    }

    protected function isNotRequired()
    {
        return !$this->hasRule('required') && !$this->params[1];
    }

    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }
}