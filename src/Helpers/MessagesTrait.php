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
     * Setup custom error messages
     *
     * @param $rule
     * @param $message
     */
    public static function setDefaultMessage($rule, $message)
    {
        self::$errorBag->setDefaultMessage($rule, $message);
    }

    /**
     * Mass setup of default message
     * @param array $rulesMessages
     */
    public static function setDefaultMessages(array $rulesMessages)
    {
        self::$errorBag->setDefaultMessages($rulesMessages);
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

}
