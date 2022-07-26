<?php

namespace Battleship\App;

use Exception;

class Autoloader
{
    public static function register($path): void
    {
        spl_autoload_register(
        /**
         * @throws Exception
         */
            function () use ($path) {

                $filePath = $path . '.php';
                try {
                    require_once $filePath;
                } catch (Exception $exception) {
                    if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/logs/autoloader.log')) {
                        mkdir($_SERVER['DOCUMENT_ROOT'] . '/logs');
                        $mkFile = fopen($_SERVER['DOCUMENT_ROOT'] . '/logs/autoloader.log', "a+");
                        fclose($mkFile);
                    }
                    error_log(
                        $exception->getMessage() . PHP_EOL,
                        3,
                        $_SERVER['DOCUMENT_ROOT'] . '/logs/autoloader.log'
                    );
                    die();
                }
            }
        );
    }
}