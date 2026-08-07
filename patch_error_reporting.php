<?php
$content = file_get_contents('index.php');
$content = str_replace(
"ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(0);",
"ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);",
$content);
file_put_contents('index.php', $content);
