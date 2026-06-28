<?php
define('APP_ROOT', dirname(__FILE__));
require 'core/App.php';
require 'core/Controller.php';
require 'config/config.php';
require 'core/Database.php';
require 'app/models/Clinic.php';

class Profile extends Controller {
    private $clinicModel;

    public function __construct() {
        $this->clinicModel = $this->model('Clinic');
    }

    public function view($slug = '', $data = []) {
        if(empty($slug)) {
            echo "empty slug\n";
            return;
        }

        $clinic = $this->clinicModel->getClinicBySlug($slug);

        var_dump($clinic);

        if(!$clinic || $clinic->is_verified == 0) {
            echo "Profile not found or pending verification.\n";
            return;
        }

        echo "Success\n";
    }
}

$p = new Profile();
$p->view('smith-cardio');
