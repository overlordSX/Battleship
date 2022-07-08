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
     * @param array $patternArray массив шаблонов url адреса
     * @param $callback callable функция, которая будет соответствовать этому шаблону
     */
    public static function route(array $patternArray, callable $callback): void
    {
        foreach ($patternArray as $pattern) {
            $pattern = '/^' . str_replace('/', '\/', $pattern) . '$/';
            self::$routes[$pattern] = $callback;
        }
        //$pattern = '/^' . str_replace('/', '\/', $pattern) . '$/';
        self::$routes[$pattern] = $callback;
    }


    /**
     * @param $url string заправшиваемый url
     * @return mixed|void
     */
    public static function execute(string $url)
    {
        $found = false;
        foreach (self::$routes as $pattern => $callback) {
            if (preg_match($pattern, $url, $params)) {
                array_shift($params);
                return call_user_func_array($callback, array_values($params));
            }
        }
        if (!$found) {
            header('Location: /templates/error-404.php');
            die();
        }
    }

}