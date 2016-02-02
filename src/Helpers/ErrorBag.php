<?php
namespace Progsmile\Validator\Helpers;

use Progsmile\Validator\Rules\BaseRule;

class ErrorBag
{
    /** @var array messages passed to validator (highest priority) */
    private $userMessages = [];

    /** @var array out error messages */
    private $errorMessages = [];

    /** @var array - messages from user file */
    private $customDefaultMessages = [];

    /** @var array - messages from file Data/defaultMessages */
    private $templateErrorMessages = [];


    /**
     * ErrorBag constructor
     */
    public function __construct()
    {
        if (empty($this->templateErrorMessages)){
            $this->templateErrorMessages = require __DIR__ . '/../Data/defaultMessages.php';
        }
    }


    /**
     * Get default or overridden message
     * @param $ruleClassName
     * @return null
     */
    public function getDefaultMessage($ruleClassName)
    {
        $ruleClassName = lcfirst($ruleClassName);

        //read errors from user file
        if (isset($this->customDefaultMessages[$ruleClassName])){
            return $this->customDefaultMessages[$ruleClassName];

        //read errors from default file
        } elseif (isset($this->templateErrorMessages[$ruleClassName])) {
            return $this->templateErrorMessages[$ruleClassName];
        }

        return null;
    }


    /**
     * Erase all error messages
     */
    public function clear()
    {
        $this->errorMessages = [];
    }


    /**
     * Add new message
     * @param $fieldName
     * @param $message
     */
    public function addMessage($fieldName, $message)
    {
        $this->errorMessages[$fieldName][] = $message;
    }


    /**
     * Returns messages count
     * @return int
     */
    public function getMessagesCount()
    {
        return count($this->errorMessages);
    }


    /**
     * Get flat messages array, or all messages from field
     * @param string $field
     * @return array
     */
    public function getMessages($field = '')
    {
        if ($field){
            return isset($this->errorMessages[$field]) ? $this->errorMessages[$field] : [];
        }

        $messages = [];

        array_walk_recursive($this->errorMessages, function ($message) use (&$messages) {
            $messages[] = $message;
        });

        return $messages;
    }


    /**
     * Get 2d array with fields and messages
     * @return array
     */
    public function getRawMessages()
    {
        return $this->errorMessages;
    }


    /**
     * For each rule get it's first message
     * @return array
     */
    public function getFirstMessages()
    {
        $messages = [];
        foreach ($this->errorMessages as $fieldsMessages) {
            foreach ($fieldsMessages as $fieldMessage) {
                $messages[] = $fieldMessage;
                break;
            }
        }

        return $messages;
    }


    /**
     * Returns first message from $field or error messages array
     * @param string $field
     * @return mixed
     */
    public function getFirstMessage($field = '')
    {
        if (isset($this->errorMessages[$field])){
            $message = reset($this->errorMessages[$field]);

        } else {
            $firstMessages = $this->getFirstMessages();
            $message       = reset($firstMessages);
        }

        return $message;
    }


    /**
     * Choosing error message: custom or default
     * @param $instance
     */
    public function chooseErrorMessage(BaseRule $instance)
    {
        list($fieldName, $ruleValue) = $instance->getParams();

        $ruleName = $instance->getRuleName();

        $ruleErrorFormat = $fieldName . '.' . lcfirst($instance->getRuleName());

        if (isset($this->userMessages[$ruleErrorFormat])){
            $ruleMessages = $this->userMessages[$ruleErrorFormat];

        } else {

            $ruleMessages = $this->getDefaultMessage($ruleName);

            //for min or max rule messages
            if (is_array($ruleMessages)){
                $ruleMessages = $instance->hasRule('numeric') ? reset($ruleMessages) : array_pop($ruleMessages);
            }
        }

        $message = strtr($ruleMessages, [
                ':field:' => $fieldName,
                ':value:' => $ruleValue,
            ]
        );

        ob_flush();

        $this->addMessage($fieldName, $message);
    }

    /**
     * Setting up user messages
     * @param $userMessages
     * @return void
     */
    public function setUserMessages($userMessages)
    {
        $this->userMessages = $userMessages;
    }

    /**
     * Sets file with default messages
     * @param $filePath
     * @return void
     */
    public function setMessagesFile($filePath)
    {
        $this->customDefaultMessages = require $filePath;
    }
}