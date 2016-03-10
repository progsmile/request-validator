<?php

namespace Progsmile\Validator\Helpers;

use PDO;
use PDOException;
use Progsmile\Validator\Rules\BaseRule;

trait PdoTrait
{
    private static $pdoInstance = null;

    /**
     * Initialize PDO connection.
     *
     * @param string $connectionString - ex. (mysql:host=localhost;dbname=test)
     * @param string $user             - db username
     * @param string $password         - db password
     */
    public static function setupPDO($connectionString, $user, $password)
    {
        try {
            self::$pdoInstance = new PDO($connectionString, $user, $password);
        } catch (PDOException $e) {
            trigger_error($e->getMessage(), E_USER_ERROR);
        }
    }

    /**
     * Setup PDO instance.
     *
     * @param PDO $pdoInstance
     */
    public static function setPDO(PDO $pdoInstance)
    {
        self::$pdoInstance = $pdoInstance;
    }

    /**
     * Get PDO object for unique validators.
     *
     * @return mixed|null
     */
    public static function getPDO()
    {
        return self::$pdoInstance ?: null;
    }

    /**
     * Setup database service from available.
     *
     * @param $orm
     */
    public static function setDataProvider($orm)
    {
        self::$config[BaseRule::CONFIG_ORM] = $orm;
    }
}
