<?php
namespace Progsmile\Validator;

use Progsmile\Validator\Format\HTML as FormatHTML;

// working example
// will composer load all of the classes?

include 'Rules/RulesInterface.php';
include 'Format/FormatInterface.php';
include 'Format/HTML.php';
include 'Format/Json.php';

foreach (array_slice(scandir(__DIR__ . '/Rules'), 2) as $class) {
   if ( $class !== 'RulesInterface.php' ){
      include __DIR__ . '/Rules/' . $class;
   }
}


class Validator
{
   // #dns static field for versions 5.4, 5.5
   private static $MAP = [
      'required' => 'Field :first: is required.',
      'email'    => 'Field :first: has a bad email format.',
      'min'      => 'Field :first: should be minimum :second:.',
      'max'      => 'Field :first: should be maximum :second:.',
      'unique'   => 'Field :first: is not unique.',
      'accepted' => 'Field :first: should be accepted',
   ];

   private $isValid = true;

   private $errorMessages = [];





   public function make($data, $rules, $userMessages = [])
   {
      foreach ($rules as $fieldName => $fieldRules) {

         $groupedRules = explode('|', $fieldRules);

         foreach ($groupedRules as $concreteRule) {

            $ruleNameParam = explode(':', $concreteRule);
            $ruleName      = $ruleNameParam[0];
            $ruleParam     = isset($ruleNameParam[1]) ? $ruleNameParam[1] : '';

            $class = __NAMESPACE__ . '\\Rules\\' . ucfirst($ruleName);

            $instance = new $class;
            $instance->setParams([ $data[$fieldName], $ruleParam ]);

            $this->isValid = $instance->fire();

            if ( $this->isValid == false ){

               $ruleErrorFormat = $fieldName . '.' . $ruleName;

               if ( isset($userMessages[$ruleErrorFormat]) ){

                  $this->errorMessages[$fieldName][] = $userMessages[$ruleErrorFormat];

               } else {

                  $message = self::$MAP[$ruleName];

                  $message = strtr($message, [
                        ':first:'  => $fieldName,
                        ':second:' => $ruleParam,
                     ]
                  );

                  $this->errorMessages[$fieldName][] = $message;
               }
            }
         }
      }

      return $this;
   }





   public function isValid()
   {
      //#dns if all rules failed, and the last valid, result will be OK
      //so, return $this->isValid not good idea)

      return count($this->errorMessages) == 0;
   }





   public function messages()
   {
      return $this->errorMessages;
   }





   public function format($class = FormatHTML::class)
   {
      return (new $class)->reformat($this->errorMessages);
   }
}
