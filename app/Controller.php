<?php

class Controller
{
    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function test()
    {
        $s = new SearchResult();
    }

    public function fallback()
    {
        header("HTTP/1.1 404 Not Found");
        echo '<h1>404 Not Found</h1>';
    }

}