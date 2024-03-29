<?php

class Database
{
    private $dbh;

    const DB_NAME = 'app';
    const RESULTS_TABLE_NAME = 'search_results';

    public function __construct($options)
    {
        $this->dbh = new PDO($options['dsn'], $options['user'], $options['password']);
        $this->dbh->exec("CREATE DATABASE IF NOT EXISTS " . self::DB_NAME . " 
            CHARACTER SET utf8mb4
            COLLATE utf8mb4_unicode_ci;");
        $this->dbh->exec('USE ' . self::DB_NAME . ';');

        $this->dbh->exec('CREATE TABLE IF NOT EXISTS `' . self::RESULTS_TABLE_NAME . '` (
          `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
          `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
          `result_count` int(11) DEFAULT NULL,
          `result_values` text COLLATE utf8mb4_unicode_ci,
          `created` datetime DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
    }

    public function addResult(SearchResult $result)
    {
        $statement = $this->dbh->prepare("
          INSERT INTO " . self::RESULTS_TABLE_NAME . " 
            (url, type, result_count, result_values, created)
          VALUES(?, ?, ?, ?, NOW())
        ");

        $result = $statement->execute([
            $result->url, $result->type, $result->resultCount, json_encode($result->resultValues),
        ]);
        if (!$result) {
            return false;
        }
        return $this->dbh->lastInsertId();
    }

    /**
     * @param $id
     * @return SearchResult|false
     */
    public function getResult($id)
    {
        $stmt = $this->dbh->prepare("SELECT * FROM " . self::RESULTS_TABLE_NAME . " WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            return false;
        }
        $searchResult = $this->populateResult($row);
        return $searchResult;
    }

    private function populateResult($row)
    {
        $searchResult = new SearchResult();
        $searchResult->id = $row['id'];
        $searchResult->type = $row['type'];
        $searchResult->url = $row['url'];
        $searchResult->resultCount = $row['result_count'];
        $searchResult->resultValues = json_decode($row['result_values'], true);
        $searchResult->created = $row['created'];

        return $searchResult;
    }

    /**
     * @return SearchResult[]
     */
    public function getResults()
    {
        $stmt = $this->dbh->query("SELECT * FROM " . self::RESULTS_TABLE_NAME . " ORDER BY id DESC");
        $results = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $results[] = $this->populateResult($row);
        }

        return $results;
    }
}