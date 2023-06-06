<?php

namespace App\Core\Helpers;

/**
 * Класс для работы с очередью сообщений
 * передаваемой между страничками во время запросов
 */
class MessagesHelper
{
    /**
     * Конструктор класса
     */
    public function __construct()
    {
        $_SESSION['messages'] = isset($_SESSION['messages']) ? $_SESSION['messages'] : [];
    }

    /**
     * Возвращает очередь сообщений по указанному ключу
     *
     * @param string $key ключ очереди сообщений
     * @return array
     */
    public function get($key = "common")
    {
        $return_array = [];

        if (array_key_exists($key, $_SESSION['messages']) && is_array($_SESSION['messages'][$key])) {
            [$return_array,  $_SESSION['messages'][$key]] = [$_SESSION['messages'][$key],  []];
        }

        return $return_array;
    }

    /**
     * Добавляет сообщение в очередь по указанному ключу
     *
     * @param string $value сообщение, которое нужно добавить в очередь
     * @param string $key ключ очереди
     * @return void
     */
    public function add($value, $key = "common")
    {
        if (empty(trim($value))) {
            return;
        }

        if (!array_key_exists($key, $_SESSION['messages'])) {
            $_SESSION['messages'][$key] = [];
        }

        if (is_array($_SESSION['messages'][$key])) {
            $_SESSION['messages'][$key][] = $value;
        }
    }
}
