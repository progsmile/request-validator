<?php
namespace Progsmile\Validator\Helpers;

class ErrorBag
{
    private $errorMessages = [];

    private $customDefaultMessages = [];

    private $templateErrorMessages = [];


    public function __construct()
    {
        if (empty($this->templateErrorMessages)){
            $this->templateErrorMessages = require __DIR__ . '/../data/defaultMessages.php';
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


    public function addMessage($fieldName, $message)
    {
        $this->errorMessages[$fieldName][] = $message;
    }


    public function getMessagesCount()
    {
        return count($this->errorMessages);
    }


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


    public function getRawMessages()
    {
        return $this->errorMessages;
    }


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
}