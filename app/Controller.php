<?php

class Controller
{
    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function actionResults()
    {
        header("Content-Type: application/json");

        $data = [];
        foreach ($this->app->db->getResults() as $result) {
            $data[] = $result->toArray();
        }
        echo json_encode($data);
    }

    public function fallback()
    {
        header("HTTP/1.1 404 Not Found");
        echo '<h1>404 Not Found</h1><a href="/">Перейти на главную страницу &rarr;</a>';
    }

}