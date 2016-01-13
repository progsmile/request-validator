<?php
namespace Progsmile\Validator\Rules;

class Boolean implements RulesInterface
{
   private $params;





   public function fire()
   {
      return is_bool($this->params[0]);
   }





   public function setParams($params)
   {
      $this->params = $params;
   }
}