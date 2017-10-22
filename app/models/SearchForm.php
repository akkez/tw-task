<?php

class SearchForm extends Model
{
    public $url;
    public $type;
    public $textQuery;

    public $errorMessage;
    public $matches;

    const TYPE_IMAGE = 'image';
    const TYPE_LINK = 'link';
    const TYPE_TEXT = 'text';
    const TYPES = [self::TYPE_IMAGE, self::TYPE_LINK, self::TYPE_TEXT];

    const REGEXP_IMAGE = '#<img.+?src\s*=\s*["\'](.+?)["\'][^>]*>#i';
    const REGEXP_LINK = '#<a.+?href\s*=\s*["\'](.+?)["\'][^>]*>(.+?)</a>#i';
    const REGEXP_TEXT = '#.*{query}.*#i';

    public function validate()
    {
        if ($this->url == '') {
            $this->errorMessage = 'Url was not specified';
            return false;
        }
        if (!in_array($this->type, self::TYPES)) {
            $this->errorMessage = 'Unknown search type';
            return false;
        }
        if ($this->type == self::TYPE_TEXT && $this->textQuery == '') {
            $this->errorMessage = 'Text query was not specified';
            return false;
        }
        if (!preg_match('#^https?://[a-z0-9\\.-/]+(.*?)#i', $this->url)) {
            $this->errorMessage = 'Incorrect url';
            return false;
        }
        return true;
    }

    public function search()
    {
        $response = HttpHelper::makeRequest($this->url);
        $this->matches = [];
        if ($response === null) {
            return false;
        }

        $result = false;

        switch ($this->type) {
            case self::TYPE_IMAGE:
                $result = preg_match_all(self::REGEXP_IMAGE, $response, $matches);
                if ($result !== false) {
                    for ($i = 0; $i < count($matches[0]); $i++) {
                        $this->matches[] = [
                            'tag' => trim($matches[0][$i]),
                            'src' => $matches[1][$i],
                        ];
                    }
                }
                break;
            case self::TYPE_LINK:
                $result = preg_match_all(self::REGEXP_LINK, $response, $matches);
                if ($result !== false) {
                    for ($i = 0; $i < count($matches[0]); $i++) {
                        $this->matches[] = [
                            'tag' => trim($matches[0][$i]),
                            'href' => $matches[1][$i],
                            'title' => $matches[2][$i],
                        ];
                    }
                }
                break;
            case self::TYPE_TEXT:
                $expression = str_replace("{query}", $this->textQuery, self::REGEXP_TEXT);
                $result = preg_match_all($expression, $response, $matches);
                if ($result !== false) {
                    for ($i = 0; $i < count($matches[0]); $i++) {
                        $this->matches[] = [
                            'text' => trim($matches[0][$i]),
                        ];
                    }
                }
                break;
        }

        return ($result !== false);
    }

    public function getResult()
    {
        $model = new SearchResult();
        $model->url = $this->url;
        $model->type = $this->type;
        $model->resultCount = count($this->matches);
        $model->resultValues = $this->matches;

        return $model;
    }

}