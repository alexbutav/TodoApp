<?php
    namespace App\Controllers;

    use App\Core\View;
    use App\Core\Auth;
    use App\Core\Controller;
    use App\Models\User;

    class LoginController extends Controller{

        public function index() {
            View::render("home/login.html");
        }

        /**
         * Контроллер обрабатывающий попытку авторизации
         *
         * @return void
         */
        public function auth() {
            // проверяем авторизацию
            if(Auth::check()) $this->returnWithMessages("Вы уже авторизованы!");

            // базова валидация
            if( !isset($_POST['email']) or 
                !isset($_POST['password']) or
                empty($_POST['email']) or 
                empty($_POST['password'])
            ) $this->returnWithMessages("Не заполнены обязательные поля!");

            // обрабатываем входные данные
            $email     = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL)); 
            $password  = trim(filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS));

            // базова валидация email
            if(filter_var($email, FILTER_VALIDATE_EMAIL) === false) $this->returnWithMessages("Введён некорректный email!");

            // ищем пользователя
            if(($user = User::where('email', $email)->first()) == null) $this->returnWithMessages("Пользователь с данным email не найден!");

            if(Auth::attempt($user, $password)) $this->returnWithMessages("Вы успешно авторизованы!", "login", "messages");
            else $this->returnWithMessages("Неверно введён пароль!");
        }

        public function logout() {
            Auth::logout();
            header("Location: /login");
            // header("location:javascript://history.go(-1)");
        }
    }