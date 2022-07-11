<?php

class Database
{
    protected static ?Database $_instance = null;

    protected PDO $pdo;

    public static function getInstance(): Database
    {
        if (empty(self::$_instance)) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    protected function getPdo(): PDO
    {
        return $this->pdo;
    }

    private function __construct()
    {
        global $dbHost, $dbName, $dbCharset, $dbUser, $dbPass;


        $dsn = "mysql:host=$dbHost; dbname=$dbName; charset=$dbCharset";

        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $dbUser, $dbPass, $opt);
        } catch (Exception $exception) {
            error_log(
                $exception->getMessage() . PHP_EOL,
                3,
                $_SERVER['DOCUMENT_ROOT'] . '/logs/connection-error.log'
            );
            header('Location: /templates/error-500.php');
            die();
        }

    }

    public static function queryFetchRow($sql)
    {
        return self::getInstance()->getPdo()->query($sql)->fetch();
    }

    public static function queryFetchAll($sql): bool|array
    {
        return static::getInstance()->getPdo()->query($sql)->fetchAll();
    }

    public static function exec($sql): bool|int
    {
        return static::getInstance()->getPdo()->exec($sql);
    }

    public static function prepareAndExecute($sql, $values = null): bool
    {
        return static::getInstance()->getPdo()->prepare($sql)->execute($values);
    }

    public static function preparePdoSet($allowed, &$values, $source = array()): string
    {
        $set = '';
        $values = array();
        if (!$source) $source = &$_POST;
        foreach ($allowed as $field) {
            if (isset($source[$field])) {
                $set .= "`" . str_replace("`", "``", $field) . "`" . "=:$field, ";
                $values[$field] = $source[$field];
            }
        }
        return substr($set, 0, -2);
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
    }
}