<?php

namespace App\Core;

use App\Models\User;

class Auth
{
    /**
     * Функция авторизации пользователя по введённым данным
     *
     * @param User $user
     * @param String $password
     * @return boolean true, если авторизация прошла успешно, false, если иначе
     */
    public static function attempt(User $user, String $password)
    {
        self::checkAuth();

        if (!password_verify($password, $user->password)) {
            return false;
        }

        if (intval($user->is_admin)) {
            self::setAdmin();
        }

        $_SESSION['auth']['logged_in'] = true;

        return true;
    }

    /**
     * Проверяет установлены ли все необходимые для корректной
     * работы класса переменные, если нет, то устанавливает в нужное значение.
     *
     * @return void
     */
    protected static function checkAuth()
    {
        if (!isset($_SESSION['auth'])) {
            $_SESSION['auth'] = [];
        }

        if (!array_key_exists('logged_in', $_SESSION['auth'])) {
            $_SESSION['auth']['logged_in'] = false;
        }

        if (!array_key_exists('is_admin', $_SESSION['auth'])) {
            $_SESSION['auth']['is_admin'] = false;
        }
    }

    public static function setAdmin($value = true)
    {
        $_SESSION['auth']['is_admin'] = $value;
    }

    /**
     * Проверяет залогинился ли пользователь
     *
     * @return boolean true, если пользователь авторизован или false, если нет
     */
    public static function check()
    {
        self::checkAuth();
        return $_SESSION['auth']['logged_in'] === true ? true : false;
    }

    public static function isAdmin()
    {
        self::checkAuth();
        return $_SESSION['auth']['is_admin'] === true ? true : false;
    }

    public static function logout()
    {
        unset($_SESSION['auth']);
        self::checkAuth();
    }
}
