<?php
namespace Progsmile\Validator\Rules;

class BaseRule
{
    private $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    protected function getConfig()
    {
        return $this->config;
    }
}