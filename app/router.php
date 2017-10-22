<?php

if (php_sapi_name() == 'cli-server') {
    if (file_exists($_SERVER["DOCUMENT_ROOT"] . $_SERVER["REQUEST_URI"])) {
        return false;
    } else {
        include __DIR__ . "/app.php";
        $app = new App();
        $app->run();

        return true;
    }
}
