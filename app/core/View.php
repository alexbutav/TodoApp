<?php

namespace App\Core;

use App\Core\Helpers\MessagesHelper;
use App\Core\Auth;

class View
{
    public static function render($template, $args = [])
    {
        static $twig = null, $messages = null;

        if ($messages === null) {
            $messages = new MessagesHelper();
        }

        if ($twig === null) {
            $loader = new \Twig\Loader\FilesystemLoader('../app/views');
            $twig = new \Twig\Environment($loader);
        }

        $args['sort'] = [
            'current_field' => $_SESSION['sort']['field'],
            'current_direction' => $_SESSION['sort']['direction'],
            'fields' => ['id' => 'Порядок', 'name' => 'Имя', 'email' => 'Email'],
            'directions' => ['asc' => 'Возрастанию', 'desc' => 'Убыванию']
        ];
        $args['auth'] = ['logged_in' => Auth::check(), 'is_admin' => Auth::isAdmin()];
        $args['errors']= $messages->get('errors');
        $args['messages'] = $messages->get('messages');

        // принимаем запросы только с домена
        // убрано для работы CDN
        // header("Content-Security-Policy: default-src 'self'");

        echo $twig->render($template, $args);
    }
}
