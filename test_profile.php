<?php
define('APP_ROOT', dirname(__FILE__));
require 'core/App.php';
require 'core/Controller.php';
require 'config/config.php';
require 'core/Database.php';
require 'app/models/Clinic.php';
require 'app/controllers/Profile.php';

$p = new Profile();
$p->view('smith-cardio');
