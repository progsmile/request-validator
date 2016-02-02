<?php
namespace Progsmile\Validator\Rules;

use Progsmile\Validator\Validator;

class Unique extends BaseRule
{
    public function isValid()
    {
        if ($this->isNotRequiredAndEmpty()){
            return true;
        }

        $config = $this->getConfig();

        $field = $this->params[0];
        $value = $this->params[1];
        $table = $this->params[2];

        //if PDO is provided, make request from it
        /** @var \PDO $db */
        if ($db = Validator::getPDO()){

            $sql  = "SELECT COUNT(*) FROM `$table` WHERE $field =:v";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':v', $value, \PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetchColumn() == 0;
        }

        //if ORM class is provided
        $instance = new $config[BaseRule::CONFIG_ORM]($field, $value, $table);

        return $instance->isUnique();
    }

    public function getMessage()
    {
        return 'Field :field: must be unique';
    }
}
