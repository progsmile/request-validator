<?php
namespace Progsmile\Validator\Rules;


class Alpha implements RulesInterface
{
   private $params;

   public function fire()
   {
      return is_string($this->params[0]) && preg_match('/^[\pL\pM]+$/u', $this->params[0]);
   }

   //@todo: some architect variants
   //we may create abstract class, just not implementing this function from class to class
   //and create abstract `fire` method, what do you think?
   public function setParams($params)
   {
      $this->params = $params;
   }
}