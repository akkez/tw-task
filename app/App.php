<?php

include __DIR__ . '/Controller.php';
include __DIR__ . '/Database.php';
include __DIR__ . '/HttpHelper.php';
include __DIR__ . '/models/Model.php';
include __DIR__ . '/models/SearchResult.php';
include __DIR__ . '/models/SearchForm.php';

class App
{
    private $routes = [
        '/api/results' => 'actionResults',
        '/api/search' => 'actionSearch',
        '/api/detail' => 'actionDetail',

        '*' => 'fallback',
    ];
    private $dbOptions = [
        'dsn' => 'mysql:host=db;charset=utf8mb4',
        'user' => 'root',
        'password' => '',
    ];

    public $controller;
    public $db;

    public function __construct()
    {
        $this->db = new Database($this->dbOptions);
        $this->controller = new Controller($this);
    }

    public function run()
    {
        $uri = $_SERVER["REQUEST_URI"];
        if (substr_count($uri, '?') > 0) {
            list($uri, $query) = explode('?', $uri, 2);
        } else {
            $query = '';
        }

        foreach ($this->routes as $route => $action) {
            if ($route === $uri && method_exists($this->controller, $action) || $route === '*') {
                $this->controller->$action();
                return;
            }
        }
    }

}