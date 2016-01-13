<?php
namespace Progsmile\Validator\Rules;

class Accepted implements RulesInterface
{
   private $params;





   public function fire()
   {
      return isset($this->params[0]);// && in_array($this->params[0], ['yes', 'on', 1, true]);
   }





   public function setParams($params)
   {
      $this->params = $params;
   }
}