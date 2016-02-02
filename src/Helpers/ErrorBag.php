<?php
namespace Progsmile\Validator\Helpers;

use Progsmile\Validator\Rules\BaseRule;

class ErrorBag
{
    /** @var array messages passed to validator (highest priority) */
    private $userMessages = [];

    /** @var array out error messages */
    private $errorMessages = [];


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

        $ruleErrorFormat = $fieldName . '.' . lcfirst($instance->getRuleName());

        if (isset($this->userMessages[$ruleErrorFormat])){
            $ruleErrorMessage = $this->userMessages[$ruleErrorFormat];

        } else {
            $ruleErrorMessage = $instance->getMessage();
        }

        $this->addMessage($fieldName, strtr($ruleErrorMessage, [
                ':field:' => $fieldName,
                ':value:' => $ruleValue,
            ]
        ));
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
}