<?php
namespace Progsmile\Validator\DbProviders;

use Progsmile\Validator\Contracts\Frameworks\OrmInterface;

class DefaultPDO implements OrmInterface
{
    private $table;
    private $attribute;
    private $value;

    public function __construct($attribute, $value, $table)
    {
        $this->attribute = $attribute;
        $this->value     = $value;
        $this->table     = $table;
    }

    public function isUnique()
    {
        try {
            $db = new \PDO('mysql:host=localhost;dbname=valid;charset=utf8', 'root', '123');
        } catch (\PDOException $e) {
            die('Connection refused');
        }


        $result = $db->prepare("SELECT COUNT (*) FROM $this->table WHERE $this->attribute=:p");
        $result->bindParam(':p', $this->value);
        $result->execute();

        return $result->fetchColumn(0) == 0;
    }
}