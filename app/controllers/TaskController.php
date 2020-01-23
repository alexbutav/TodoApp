<?php
    namespace App\Controllers;

    use App\Core\Controller;
    use App\Core\Auth;
    use App\Models\Task;

    class TaskController extends Controller {

        public function __construct(){
        }

        public function add() {

            // базовая валидация
            $required = ['name', 'email', 'body'];
            foreach($required as $field_name)
                if( !isset($_POST[$field_name]) or empty($_POST[$field_name])) $this->returnWithMessages("Все поля обязательны для заполнения!", "/");
            
            // обрабатываем входные данные
            $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL)); 
            $name  = trim(filter_var(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING), FILTER_SANITIZE_FULL_SPECIAL_CHARS));
            $body  = trim(filter_var(filter_input(INPUT_POST, 'body', FILTER_SANITIZE_STRING), FILTER_SANITIZE_FULL_SPECIAL_CHARS));
            
            // базова валидация email
            if(filter_var($email, FILTER_VALIDATE_EMAIL) === false) $this->returnWithMessages("Введён некорректный email!", "/");

            $task = Task::create([
                'email' => $email,
                'name' => $name,
                'body' => $body,
            ]);
            
            if($task) $this->returnWithMessages("Задача успешно создана!", "/", "messages");
            else $this->returnWithMessages("Произошла ошибка во время создания задачи!", "/");
        }

        public function edit() {
            // хорошо бы через middleware это где надо делать
            if( !Auth::check()) $this->returnWithMessages("Необходима атворизация!", "/login");
            if( !Auth::isAdmin()) $this->returnWithMessages("Недостаточно прав!", "/");
            
            // фильтруем входные данные
            $id   = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $done = filter_input(INPUT_POST, 'done', FILTER_VALIDATE_BOOLEAN) ?: false;
            $body = trim(filter_var(filter_input(INPUT_POST, 'body', FILTER_SANITIZE_STRING), FILTER_SANITIZE_FULL_SPECIAL_CHARS));

            if(($task = Task::find($id)) == null ) $this->returnWithMessages("Указанная Вами задача не найдена!", "/");

            $edited = $task->body == $body ? false : true;

            $task->body   = $body;
            $task->done   = $done;
            $task->edited = $edited;

            $task->save();
            
            $this->returnWithMessages("Данные успешно обновлены!", "/", "messages");
        }
    }