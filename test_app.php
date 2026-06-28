<?php
$_GET['url'] = 'profile/view/smith-cardio';
define('APP_ROOT', dirname(__FILE__));
require 'core/App.php';
require 'core/Controller.php';
require 'config/config.php';
require 'core/Database.php';

$app = new App();
