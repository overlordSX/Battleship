<?php

/**
 * Класс реализующий фукнционал роутинга
 */
class Router
{
    //хранит маршруты, маршруту соотвествует функция
    private static array $routes = [];

    //запрет на создание и клонирование
    private function __construct()
    {
    }

    private function __clone()
    {
    }


    /**
     * к началу автоматически добавляется /^, к концу $/, все / экранируются \/
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
     * @var callable $callback
     * @var string $url заправшиваемый url
     */
    public static function execute(string $url)
    {
        //echo $url . '<br>';

        $found = false;
        foreach (self::$routes as $pattern => $routeParams) {
            if (preg_match($pattern, $url, $params) and $routeParams['requestedMethod'] === $_SERVER['REQUEST_METHOD']) {
                array_shift($params);
                //echo $routeParams;

                //var_dump( $params);
                $found = true;
                return call_user_func_array($routeParams['callback'], array_values($params));
            }
        }
        /*if (!$found) {
            header('Location: /error-404');
            die();
        }*/
    }

}