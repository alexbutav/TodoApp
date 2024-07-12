<?php

namespace App\Controllers;

use App\Core;
use App\Models\Task;

class HomeController extends Core\Controller
{
    protected const DEFAULT_ITEMS_PER_PAGE = 3;

    protected $sort_field;
    protected $sort_direction;

    public function __construct()
    {
        parent::__construct();

        $this->initSortingParams();
    }

    protected function initSortingParams()
    {
        $_SESSION['sort'] = $_SESSION['sort'] ?? [];
        $_SESSION['sort']['field'] = $_SESSION['sort']['field'] ?? 'id';
        $_SESSION['sort']['direction'] = $_SESSION['sort']['direction'] ?? 'asc';

        [$this->sort_field, $this->sort_direction] = [$_SESSION['sort']['field'], $_SESSION['sort']['direction']];
    }

    public function index($page_id = 1)
    {
        $items_pre_page = self::DEFAULT_ITEMS_PER_PAGE;

        $tasks = Task::orderBy($this->sort_field, $this->sort_direction)
            ->paginate($items_pre_page, ['id', 'name', 'email', 'body', 'done', 'edited'], 'page', $page_id);

        Core\View::render("home/index.html", ['current_page' => $page_id, 'tasks' => $tasks]);
    }

    public function changeSortField($field = 'id')
    {
        if (!in_array($field, ['id', 'name', 'email'])) {
            $this->returnWithMessages("Недопустимое поле сортировки!", "/");
        }

        $_SESSION['sort']['field'] = $field;

        $this->returnWithMessages("Поле сортировки изменено!", "/", "messages");
    }

    public function changeSortDirection($direction = 'asc')
    {
        if (!in_array($direction, ['asc', 'desc'])) {
            $this->returnWithMessages("Недопустимое направление сортировки!", "/");
        }

        $_SESSION['sort']['direction'] = $direction;

        $this->returnWithMessages("Направление сортировки изменено!", "/", "messages");
    }
}
