<?php
namespace Progsmile\Validator\Helpers;

trait MessagesTrait
{
    /** @var ErrorBag */
    private static $errorBag = null;

    /**
     * Returns all error messages | Or all error messages from concrete field
     * @param string $field
     * @return array
     */
    public function getMessages($field = '')
    {
        return self::$errorBag->getMessages($field);
    }


    /**
     * Returns first error message from each fields
     * @return array
     */
    public function getFirstMessages()
    {
        return self::$errorBag->getFirstMessages();
    }

    /**
     * Returns first error message from concrete field or from validation stack
     * @param string $field
     * @return mixed|string
     */
    public function getFirstMessage($field = '')
    {
        return self::$errorBag->getFirstMessage($field);
    }

    /**
     * Returns custom message by rule
     * @param $ruleClassName
     * @return mixed
     */
    public static function getDefaultMessage($ruleClassName)
    {
        return self::$errorBag->getDefaultMessage($ruleClassName);
    }

    /**
     * Reformat messages provided by HTML, JSON or custom FormatInterface classes
     * @param string $class
     * @return mixed
     */
    public function format($class = 'Progsmile\Validator\Format\HTML')
    {
        return (new $class)->reformat(self::$errorBag->getRawMessages());
    }


    /**
     * Get 2d array with fields and messages
     * @return array
     */
    public function getRawMessages()
    {
        return self::$errorBag->getRawMessages();
    }

}
