<?php
namespace Progsmile\Validator\Helpers;

class FieldsErrorBag
{
    /** @var string magic field name */
    private $fieldName = '';

    /** @var ErrorBag error bag */
    private $errorBag = null;

    /**
     * FieldsErrorBag constructor.
     * @param ErrorBag $errorBag
     */
    public function __construct(ErrorBag $errorBag)
    {
        $this->errorBag = $errorBag;
    }

    /**
     * Get first message, by query or by rule type
     * @param string $rule
     * @return bool|string|array
     */
    public function first($rule = '')
    {
        $allMessages = $this->errorBag->messages($this->fieldName);

        if (isset($allMessages[$rule])){
            return $allMessages[$rule];
        }

        return $this->fails() ? $this->errorBag->first($this->fieldName) : false;
    }

    /**
     * Get fields messages
     * @return array
     */
    public function messages()
    {
        return $this->fails() ? $this->errorBag->messages($this->fieldName) : [];
    }

    /**
     * If result is invalid
     * @return bool
     */
    public function fails()
    {
        return $this->errorBag->has($this->fieldName);
    }

    /**
     * If result is valid
     * @return bool
     */
    public function passes()
    {
       return !$this->fails();
    }

    /**
     * Setting up magic field
     * @param $fieldName
     * @return $this
     */
    public function setField($fieldName)
    {
        $this->fieldName = $fieldName;

        return $this;
    }
}
