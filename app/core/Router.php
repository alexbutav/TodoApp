<?php

namespace App\Core;

/**
 * Маршрутизатор. Добавляет пути в таблицу маршрутов, после чего
 * сопоставляет их с указанными действиями контроллеров или функций.
 */
class Router
{
    protected $url = null;
    /** параметры полученые из URI */
    protected $params = [];
    /**  таблица маршрутизации */
    protected $routes = [];
    /**  контроллер */
    protected $controller = "";
    /**  метод контроллера */
    protected $controller_action = "";
    /**  переданное замыкание */
    protected $action = null;

    /**
     * Конструктор класса
     *
     * @param string $url - URL которому нужно сопоставить маршрут
     */
    public function __construct($url = '')
    {
        $this->url = $url ?: $_SERVER['REQUEST_URI'];
    }

    /**
     * Добавляет маршрут в таблицу маршрутизации
     *
     * @param string $route   строка маршрута
     * @param mixed $callback соответствующее действие, может быть строкой вида ControllerName@action
     * или функцией
     * @return void
     */
    public function add($route, $callback)
    {
        $controller = $function = $action = "";

        $route = $this->routeToReg($route);

        // елси $callback - строка вида Controller@action,
        // то преобразуем его в соответсвующий метод
        if (is_string($callback) && !is_numeric($callback)) {
            if (preg_match('/^(?P<controller>[A-Za-z_]+)@(?P<action>[A-Za-z_]+)$/', $callback, $matches)) {
                ["controller" => $controller, "action" => $action] = $matches;
            }
        } elseif (is_callable($callback)) {
            $function = $callback;
        } else {
            throw new \TypeError("Error, invalid function arguments");
        }

        $this->routes[$route] = [
            'controller' => $controller,
            'action' => $action,
            'function' => $function
        ];
    }

    /**
     * Преобразует маршрут в регулярное выражение
     *
     * @param string $route
     *
     * @return string
     */
    public function routeToReg($route)
    {
        // Экранируем прямые слеши
        $route = preg_replace('/\//', '\\/', $route);

        // Заменяем переменные вида {controller} на соответствующую именованную подмаску
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);

        // то же самое, но с учетом кастомных регулярок {id:\d+}
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);

        return "/^" . $route . "$/i";
    }

    /**
     * Сопоставляет регулярки из таблицы маршрутизации с URL,
     * помещает найденные параметры в свойство $params
     *
     * @return boolean true, если найден соответствующий маршрут, иначе false
     */
    protected function match()
    {
        foreach ($this->routes as $route => $props) {
            if (preg_match($route, $this->url, $matches)) {
                // обходим все подмаски и выбираем только те,
                // у которых ключ - строка, которая и является именем параметра
                foreach ($matches as $key => $match) {
                    if (is_string($key) && !empty($match)) {
                        $this->params[$key] = $match;
                    }
                }

                // запоминаем actions соответствующие маршруту
                [
                    'controller' => $this->controller,
                    'action' => $this->controller_action,
                    'function' => $this->action
                ] = $props;

                return true;
            }
        }

        return false;
    }

    /**
     * Диспетчер маршрутов. Если нахродит сопоставление из таблицы маршрутов
     * то вызывает указанное действие.
     *
     * @return void
     */
    public function dispatch()
    {
        if ($this->match()) {
            $action = null;

            if ($this->action) {
                $action = $this->action;
                return call_user_func_array($action, $this->params);
            }

            $controller = "App\Controllers\\$this->controller";

            if (class_exists($controller)) {
                $controller_instance = new $controller();

                if (method_exists($controller_instance, $this->controller_action)) {
                    call_user_func_array([$controller_instance, $this->controller_action], array_values($this->params));
                }
            }
        } else {
            // 404
            header("HTTP/1.0 404 Not Found");
            View::render("404.html");
        }
    }
}
