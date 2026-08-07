<?php
if (preg_match('/\.(?:png|jpg|jpeg|gif|css|js)$/', $_SERVER["REQUEST_URI"])) {
    return false;    // serve the requested resource as-is.
} else {
    // If the path doesn't start with /index.php, set $_GET['url'] manually
    $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    if ($path !== 'index.php' && $path !== '') {
        $_GET['url'] = $path;
    }
    require 'index.php';
}
