<?php

/**
 * Класс реализующий фукнционал роутинга
 */
class Router
{
    //хранит маршруты, маршруту соотвествует фукнция
    private static array $routes = array();

    //запрет на создание и клонирование
    private function __construct()
    {
    }

    private function __clone()
    {
    }


    /**
     * @param $pattern string шаблон url-адреса
     * @param $callback callable функция, которая будет соответствовать этому шаблону
     */
    public static function route(string $pattern, callable $callback): void
    {
        $pattern = '/^' . str_replace('/', '\/', $pattern) . '$/';
        self::$routes[$pattern] = $callback;
    }


    /**
     * @param $url string заправшиваемый url
     * @return mixed|void
     */
    public static function execute(string $url)
    {
        foreach (self::$routes as $pattern => $callback) {
            if (preg_match($pattern, $url, $params)) {
                array_shift($params);
                return call_user_func_array($callback, array_values($params));
            }
        }
    }

}