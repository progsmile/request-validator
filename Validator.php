<?php

class Validator
{

   protected $errorMessages = [];


   protected $data = [];


   protected $rules = [];


   protected $userMessages = [];


   protected static $acceptedRules = [
      'email', 'min', 'unique', 'required',
   ];





   public function __construct(array $data, array $rules, array $messages = [])
   {
      foreach (func_get_args() as $arg) {
         if ( !is_array($arg) ){
            throw new \Exception('Can\'t create validator. Variables should be arrays');
         }
      }

      $this->data         = $data;
      $this->rules        = $rules;
      $this->userMessages = $messages;

      $this->make();
   }





   private function make()
   {
      //iterating through rules array
      foreach ($this->rules as $fieldName => $fieldRules) {

         //explode rules
         $groupedRules = explode('|', $fieldRules);

         //iterating each group of rule
         foreach ($groupedRules as $concreteRule) {

            $ruleNameParam = explode(':', $concreteRule);
            $ruleName      = $ruleNameParam[0];
            $ruleParam     = isset($ruleNameParam[1]) ? $ruleNameParam[1] : '';

            $isValid = forward_static_call_array(['Multiple\Shared\Helpers\Validator',
               'validate' . ucfirst($ruleName)], [$this->data[$fieldName], $ruleParam]);

            if ( !$isValid ){
               $ruleErrorFormat = $fieldName . '.' . $ruleName;

               //if user passed his message, use his
               if ( isset($this->userMessages[$ruleErrorFormat]) ){
                  $this->errorMessages[] = $this->userMessages[$ruleErrorFormat];

                  //otherwise, get default
               } else {
                  $this->errorMessages[] = static::getDefaultError($fieldName, $ruleName, $ruleParam);
               }
            }
         }
      }
   }





   /*********************************************************************
    * Public methods for validator
    *********************************************************************/

   public function isValid()
   {
      return count($this->errorMessages) == 0;
   }





   public function appendMessage($message)
   {
      $this->errorMessages[] = $message;
   }



   public function prependMessage($message)
   {
      array_unshift($this->errorMessages, $message);
   }




   public function getMessages()
   {
      return $this->errorMessages;
   }





   public function hasMessages()
   {
      return count($this->errorMessages) > 0;
   }





   /**
    * Get error by default
    *
    * @param $fieldName
    * @param $ruleName
    * @param $ruleParam
    * @return string
    */
   protected static function getDefaultError($fieldName, $ruleName, $ruleParam)
   {
      switch ($ruleName) {

         case 'required':
            return 'Field ' . $fieldName . ' is required';

         case 'min':
            return 'Field ' . $fieldName . ' should be minimum ' . $ruleParam . ' chars';

         case 'unique':
            return 'Field ' . $fieldName . ' should be unique';

         case 'email' :
            return 'Field ' . $fieldName . ' has bad format';

         // . . . .
      }

      return 'unvalidated error';
   }





   /*********************************************************************
    * Methods for validating
    *********************************************************************/


   protected static function validateRequired($value)
   {
      return (bool) $value;
   }





   protected static function validateMin($value, $min)
   {
      return strlen($value) >= $min;
   }





   protected static function validateMax($value, $max)
   {
      return strlen($value) <= $max;
   }





   protected static function validateEmail($value)
   {
      return (bool) filter_var($value, FILTER_VALIDATE_EMAIL);
   }





   protected static function validateUnique($value, $model)
   {
      $model = new $model;

      if ( !$model instanceof IUniqueness ){
         throw new \Exception('Model with unique validator should implement IUniqueness interface');
      }

      return forward_static_call_array([$model, 'count'], [[
         'conditions' => $model->getUniqueFieldName() . ' = ?0',
         'bind'       => [$value],
      ]]) == 0;
   }
}
