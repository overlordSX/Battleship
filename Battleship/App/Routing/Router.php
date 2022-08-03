<?php

namespace Battleship\App\Routing;

use Battleship\App\Controllers\ControllerInterface;
use Exception;

/**
 * Класс реализующий фукнционал роутинга
 */
class Router
{
    private static array $routes = [];

    private function __construct()
    {
    }

    private function __clone()
    {
    }


    /**
     * к началу автоматически добавляется /^, к концу $/, все / экранируются \/
     * @param string $requestedMethod
     * @param array $patternArray массив шаблонов url адреса
     * @param array $classNameAndMethod
     * @throws Exception
     */
    public static function route(string $requestedMethod, array $patternArray, array $classNameAndMethod): void
    {
        $class = new $classNameAndMethod[0];
        $method = $classNameAndMethod[1];
        if (!is_subclass_of($class, ControllerInterface::class)) {
            throw new Exception('Такого контроллера нет');
        }

        foreach ($patternArray as $pattern) {
            $pattern = '/^' . str_replace('/', '\/', $pattern) . '$/';

            self::$routes[$pattern] = ['callback' => [$class, $method], 'requestedMethod' => $requestedMethod];
        }
    }


    /**
     * @return mixed|void
     * @var string $url заправшиваемый url
     */
    public static function execute(string $url)
    {

        static $isFound = false;
        foreach (self::$routes as $pattern => $routeParams) {
            if (preg_match($pattern, $url, $params) and $routeParams['requestedMethod'] === $_SERVER['REQUEST_METHOD']) {
                array_shift($params);

                $isFound = true;
                return call_user_func_array($routeParams['callback'], array_values($params));
            }
        }
        if (!$isFound) {
            die();
        }
    }

}