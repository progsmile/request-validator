<?php
namespace Progsmile\Validator\Contracts\Rules;

interface RulesInterface
{
    /**
     * Will the process to check if it is valid or not
     *
     * @return boolean Return the result if valid or not
     */
    public function isValid();

    /**
     * Set the value of the inputted attributes
     *
     * @param array $params
     */
    public function setParams($params);

    /**
     * Get the message if error occured
     *
     * @return string
     */
    public function getMessage();
}
