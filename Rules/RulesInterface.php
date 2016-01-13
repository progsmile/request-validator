<?php
namespace Progsmile\Validator\Rules;

interface RulesInterface
{
    /**
     * Will the process to check if it is valid or not
     *
     * @return boolean Return the result if valid or not
     */
    public function fire();

    /**
     * Set the value of the inputted attributes
     *
     * @param array $params
     */
    public function setParams($params);
}
