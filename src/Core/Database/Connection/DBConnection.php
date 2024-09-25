<?php
declare(strict_types=1);

namespace Lanser\MyFreamwork\Core\Database\Connection;

use Lanser\MyFreamwork\Core\Config\Config;
use PDO;
use PDOException;

class DBConnection
{
    private static $dbConnectionInstance = null;

    private function __construct()
    {

    }

    public static function getDBConnectionInstance(): PDO|bool|null
    {

        if (self::$dbConnectionInstance == null) {
            $DBConnectionInstance = new DBConnection();
            self::$dbConnectionInstance = $DBConnectionInstance->dbConnection();
        }

        return self::$dbConnectionInstance;

    }

    private function dbConnection(): bool|PDO
    {

        $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8");
        try {
            return new PDO("mysql:host=" . Config::get('database.DBHOST') . ";dbname=" . Config::get('database.DBNAME'), Config::get('database.DBUSERNAME'), Config::get('database.DBPASSWORD'), $options);
        } catch (PDOException $e) {
            echo "error in database connection: " . $e->getMessage();
            return false;
        }

    }


    public static function newInsertId(): bool|string
    {
        return self::getDBConnectionInstance()->lastInsertId();
    }
}