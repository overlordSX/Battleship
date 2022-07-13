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

        $dsnFormat = 'mysql:host=%s; dbname=%s; charset=%s';
        $dsn = sprintf($dsnFormat, $dbHost, $dbName, $dbCharset);

        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $dbUser, $dbPass, $opt);
        } catch (Exception $exception) {
            //todo #1 добавить проверку на создание файла, и директории под него
            error_log(
                $exception->getMessage() . PHP_EOL,
                3,
                $_SERVER['DOCUMENT_ROOT'] . '/logs/connection-error.log'
            );
            header('Location: /error-500');
            die();
        }

    }

    public static function queryFetchRow($sql, $values = null)
    {
        //todo #2 почему такой вариант не работает?
        //static::getInstance()->getPdo()->prepare($sql)->execute($values)->fetch();

        $pdo = static::getInstance()->getPdo();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);

        return $stmt->fetch();
    }

    public static function queryFetchAll($sql, $values = null): bool|array
    {
        $pdo = static::getInstance()->getPdo();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);

        return $stmt->fetchAll();
    }

    public static function exec($sql): bool|int
    {
        return static::getInstance()->getPdo()->exec($sql);
    }

    public static function prepareAndExecute($sql, $values = null): bool
    {
        return static::getInstance()->getPdo()->prepare($sql)->execute($values);
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
    }
}