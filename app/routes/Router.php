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
     * @param string|ControllerInterface $className
     * @param string $method
     * @throws Exception
     */
    //TODO сделать ClassName и 'method'
    public static function route(array $patternArray, string $className, string $method): void
    {
        if (!is_a($className, ControllerInterface::class)) {
            throw new Exception('Такого контроллера нет');
        }

        $class = $className;

        foreach ($patternArray as $pattern) {
            $pattern = '/^' . str_replace('/', '\/', $pattern) . '$/';
            self::$routes[$pattern] = $class->$method;
        }
    }


    /**
     * @var string $url заправшиваемый url
     * @var callable $callback
     * @return mixed|void
     */
    public static function execute(string $url)
    {
        $found = false;
        foreach (self::$routes as $pattern => $callback) {
            if (preg_match($pattern, $url, $params)) {
                array_shift($params);
                //echo $callback;
                return call_user_func_array($callback, array_values($params));
            }
        }
        if (!$found) {
            header('Location: /error-404');
            die();
        }
    }

}