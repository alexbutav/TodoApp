<?php

namespace App\Core;

use App\Core\Helpers\MessagesHelper;

abstract class Controller
{
    protected $messages;

    public function __construct()
    {
        // инициализируем межстраничную очередь сообщений
        $this->messages = new MessagesHelper();
    }

    public function index()
    {
    }

    /**
     * Добавляет в очередь сообщений $queue_name сообщение $message
     *  и провзиодит редирект с остановкой выполнения текущего скрипта.
     *
     * @param string $message сообщение, которое добавится в очередь
     * @param string $location путь перенаправления
     * @param string $queue_name имя очереди в которую будет добавлено сообщение
     * @return void
     */
    protected function returnWithMessages($message, $location = "/login", $queue_name = "errors")
    {
        if ($this->messages === null) {
            $this->messages = new MessagesHelper();
        }

        $this->messages->add($message, $queue_name);
        header("Location: $location");

        die();
    }
}
