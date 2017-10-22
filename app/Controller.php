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
        $data = [];
        foreach ($this->app->db->getResults() as $result) {
            $data[] = $result->toArray();
        }
        return $this->json($data);
    }

    public function actionDetail()
    {
        $id = $_GET['id'] ?? null;
        $row = $this->app->db->getResult($id);
        if ($row === false) {
            return $this->json([]);
        }
        return $this->json($row->toArray());
    }

    public function actionSearch()
    {
        $searchForm = new SearchForm();
        $searchForm->url = $_POST['url'] ?? null;
        $searchForm->type = $_POST['type'] ?? null;
        $searchForm->textQuery = $_POST['query'] ?? null;

        if (!$searchForm->validate()) {
            return $this->json(['error' => $searchForm->errorMessage]);
        }

        $result = $searchForm->search();
        if (!$result) {
            return $this->json(['error' => 'Не удалось сделать запрос к URL']);
        }

        $model = $searchForm->getResult();
        $id = $this->app->db->addResult($model);
        return $this->json(['message' => 'Найдено '. $model->resultCount .' совпадений', 'id' => $id]);
    }

    public function fallback()
    {
        header("HTTP/1.1 404 Not Found");
        echo '<h1>404 Not Found</h1><a href="/">Перейти на главную страницу &rarr;</a>';
    }

    private function json($object)
    {
        header("Content-Type: application/json");
        echo json_encode($object);
        return true;
    }

}