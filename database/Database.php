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
        require "config.php";

        $dsn = "mysql:host=$host; dbname=$db; charset=$charset";

        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $opt);
        } catch (Exception $exception) {
            error_log($exception->getMessage().PHP_EOL, 3, $_SERVER['DOCUMENT_ROOT'].'/connection-error.log');
            header('Location: .templates/error-500.php');
            die();
        }

    }

    public static function query($sql): bool|PDOStatement
    {
        return self::$_instance->query($sql);
    }

    public static function queryFetchAll($sql): bool|array
    {
        return static::getInstance()->getPdo()->query($sql)->fetchAll();
    }

    public static function exec($sql): bool|int
    {
        return static::getInstance()->getPdo()->exec($sql);
    }


    private function __clone()
    {
    }

    public function __wakeup()
    {
    }
}