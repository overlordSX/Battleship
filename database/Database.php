<?php

class Database
{
    protected static Database $_instance;


    public static function getInstance(): Database
    {
        if (empty(self::$_instance)) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    private function __construct()
    {
        $host = '192.168.0.2';
        $db = 'tdb_oloviev';
        $user = 'dbu_oloviev';
        $pass = 'li3ot3uo';
        $charset = 'utf8';

        $dsn = "mysql:host=$host; dbname=$db; charset=$charset";

        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        return new PDO($dsn, $user, $pass, $opt);
    }

    private function __clone()
    {
    }

    //TODO #1 тут вопрос, в статье было private, но при запуске оно говорило что нужен public
    public function __wakeup()
    {
    }
}