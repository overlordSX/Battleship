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
                //echo $filePath;
                if (file_exists($filePath)) {
                    require_once $filePath;
                } else {
                    throw new Exception('Файл, подключаемый через Autoloader не найден.');
                }
            }
        );
    }
}