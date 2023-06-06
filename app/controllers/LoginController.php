<?php

namespace App\Controllers;

use App\Core\View;
use App\Core\Auth;
use App\Core\Controller;
use App\Models\User;

class LoginController extends Controller
{
    protected const LOGIN_CREDENTIAL_NAME = 'email';
    protected const PASSWORD_CREDENTIAL_NAME = 'password';

    public function index()
    {
        View::render("home/login.html");
    }

    /**
     * Контроллер обрабатывающий попытку авторизации
     *
     * @return void
     */
    public function auth()
    {
        $this->checkIfAlreadyAuthenticated();

        $this->validateAuthData();

        $this->authByCredentials();
    }

    protected function checkIfAlreadyAuthenticated()
    {
        if (Auth::check()) {
            $this->returnWithMessages("Вы уже авторизованы!");
        }
    }

    protected function validateAuthData()
    {
        if (!isset($_POST[self::LOGIN_CREDENTIAL_NAME])
            || !isset($_POST[self::PASSWORD_CREDENTIAL_NAME])
            || empty($_POST[self::LOGIN_CREDENTIAL_NAME])
            || empty($_POST[self::PASSWORD_CREDENTIAL_NAME])
        ) {
            $this->returnWithMessages("Не заполнены обязательные поля!");
        }
    }

    protected function authByCredentials()
    {
        $email = trim(filter_input(INPUT_POST, self::LOGIN_CREDENTIAL_NAME, FILTER_SANITIZE_EMAIL));
        $password = trim(filter_input(INPUT_POST, self::PASSWORD_CREDENTIAL_NAME, FILTER_SANITIZE_FULL_SPECIAL_CHARS));

        // базовая валидация email
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $this->returnWithMessages("Введён некорректный email!");
        }

        // ищем пользователя
        /** @var User|null $user */
        $user = User::where('email', $email)->first();
        if ($user === null) {
            $this->returnWithMessages("Пользователь с данным email не найден!");
        }

        if (Auth::attempt($user, $password)) {
            $this->returnWithMessages("Вы успешно авторизованы!", "login", "messages");
        } else {
            $this->returnWithMessages("Неверно введён пароль!");
        }
    }

    public function logout()
    {
        Auth::logout();

        header("Location: /login");
    }
}
