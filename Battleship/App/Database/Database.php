<?php

namespace Battleship\App\Database;

use Exception;
use PDO;

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
            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/logs/connection-error.log')) {
                mkdir($_SERVER['DOCUMENT_ROOT'] . '/logs');
                $mkFile = fopen($_SERVER['DOCUMENT_ROOT'] . '/logs/connection-error.log', "a+");
                fclose($mkFile);
            }
            error_log(
                $exception->getMessage() . PHP_EOL,
                3,
                $_SERVER['DOCUMENT_ROOT'] . '/logs/connection-error.log'
            );
            header('Location: /error-500');
            die();
        }

    }

    public static function queryFetchRow($sql, $values = null): bool|array
    {
        try {
            $pdo = static::getInstance()->getPdo();
            $stmt = $pdo->prepare($sql);
            $stmt->execute($values);

            return $stmt->fetch();
        } catch (Exception $exception) {
            self::logToFile($exception);
        }
        return false;
    }

    public static function queryFetchAll($sql, $values = null): bool|array
    {
        try {
            $pdo = static::getInstance()->getPdo();
            $stmt = $pdo->prepare($sql);
            $stmt->execute($values);

            return $stmt->fetchAll();
        } catch (Exception $exception) {
            self::logToFile($exception);
        }
        return false;
    }

    public static function exec($sql): bool|int
    {
        try {
             return static::getInstance()->getPdo()->exec($sql);
        } catch (Exception $exception) {
            self::logToFile($exception);
        }
        return false;
    }

    public static function prepareAndExecute($sql, $values = null): bool
    {
        try {
            return static::getInstance()->getPdo()->prepare($sql)->execute($values);
        } catch (Exception $exception) {
            self::logToFile($exception);
        }

        return false;
    }

    /**
     * @param Exception $exception
     * @return void
     */
    protected static function logToFile(Exception $exception): void
    {
        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/logs/db-error.log')) {
            mkdir($_SERVER['DOCUMENT_ROOT'] . '/logs');
            $mkFile = fopen($_SERVER['DOCUMENT_ROOT'] . '/logs/db-error.log', "a+");
            fclose($mkFile);
        }

        error_log(
            $exception->getMessage() . PHP_EOL,
            3,
            $_SERVER['DOCUMENT_ROOT'] . '/logs/db-error.log'
        );
        header('Location: /error-500');
        die();
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
    }
}