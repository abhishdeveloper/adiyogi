<?php
class Admin extends Controller {
    private $settingModel;
    private $userModel;

    public function __construct() {
        // Enforce superadmin access
        if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'superadmin') {
            header('location: /auth/login');
            exit;
        }

        $this->settingModel = $this->model('Setting');
        $this->userModel = $this->model('User');
    }

    // Default admin dashboard
    public function index() {
        $data = [
            'title' => 'Admin Dashboard'
        ];
        $this->view('admin/index', $data);
    }

    // System Settings
    public function settings() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!$this->validateCsrfToken($_POST['csrf_token'])) {
                die("CSRF token validation failed.");
            }

            $this->settingModel->updateSetting('google_client_id', trim($_POST['google_client_id']));
            $this->settingModel->updateSetting('google_client_secret', trim($_POST['google_client_secret']));

            $data = [
                'google_client_id' => trim($_POST['google_client_id']),
                'google_client_secret' => trim($_POST['google_client_secret']),
                'success_msg' => 'Settings updated successfully.'
            ];

            $this->view('admin/settings', $data);
        } else {
            $data = [
                'google_client_id' => $this->settingModel->getSetting('google_client_id'),
                'google_client_secret' => $this->settingModel->getSetting('google_client_secret'),
                'success_msg' => ''
            ];
            $this->view('admin/settings', $data);
        }
    }
}
