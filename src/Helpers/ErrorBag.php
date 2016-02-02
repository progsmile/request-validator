<?php
namespace Progsmile\Validator\Helpers;

use Progsmile\Validator\Rules\BaseRule;

class ErrorBag
{
    private $userMessages = [];

    private $errorMessages = [];

    private $customDefaultMessages = [];

    private $templateErrorMessages = [];


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
        if (isset($this->customDefaultMessages[$ruleClassName])){
            return $this->customDefaultMessages[$ruleClassName];

        } elseif (isset($this->templateErrorMessages[$ruleClassName])) {
            return $this->templateErrorMessages[$ruleClassName];
        }

        return null;
    }


    /**
     * Setup default message for rule
     * @param $rule
     * @param $message
     */
    public function setDefaultMessage($rule, $message)
    {
        $this->customDefaultMessages[ucfirst($rule)] = $message;
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
     * Mass setting default messages
     * @param $rulesMessages
     */
    public function setDefaultMessages($rulesMessages)
    {
        foreach ($rulesMessages as $rule => $message) {
            $this->setDefaultMessage($rule, $message);
        }
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
            $message = $this->userMessages[$ruleErrorFormat];

        } else {

            $ruleMessages = $this->getDefaultMessage($ruleName);

            //for min or max rule messages
            if (is_array($ruleMessages)){
                $ruleMessages = $instance->hasRule('numeric') ? reset($ruleMessages) : array_pop($ruleMessages);
            }

            $message = strtr($ruleMessages, [
                    ':field:' => $fieldName,
                    ':value:' => $ruleValue,
                ]
            );
        }

        $this->addMessage($fieldName, $message);
    }

    /**
     * Setting up user messages
     * @param $userMessages
     */
    public function setUserMessages($userMessages)
    {
        $this->userMessages = $userMessages;
    }
}