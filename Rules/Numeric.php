<?php
namespace Progsmile\Validator\Rules;

class Numeric implements RulesInterface
{
   private $params;

   public function fire()
   {
      return is_numeric($this->params[0]);
   }





   public function setParams($params)
   {
      $this->params = $params;
   }
}
