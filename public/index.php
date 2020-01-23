<?php
    require "../vendor/autoload.php";

    require "../app/core/Database.php";

    // Инициализация сессии
    if (session_status() == PHP_SESSION_NONE) session_start();

    // Настраиваем маршрутизатор
    $router = new App\Core\Router();

    // main
    $router->add('/', 'HomeController@index');
    $router->add('/page/{id:\d*}', 'HomeController@index');
    
    $router->add('/sort/change/field/{field}', 'HomeController@changeSortField');
    $router->add('/sort/change/direction/{direction}', 'HomeController@changeSortDirection');

    // auth
    $router->add('/login', 'LoginController@index');
    $router->add('/logout', 'LoginController@logout');
    $router->add('/auth', 'LoginController@auth');

    // task
    $router->add('/task/add', 'TaskController@add');
    $router->add('/task/edit', 'TaskController@edit');

    // запускаем
    $router->dispatch();