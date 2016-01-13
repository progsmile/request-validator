<?php
namespace Progsmile\Validator\Rules;


class Url implements RulesInterface
{
   private $params;





   public function fire()
   {
      return filter_var($this->params[0], FILTER_VALIDATE_URL) === false;
   }





   public function setParams($params)
   {
      $this->params = $params;
   }
}