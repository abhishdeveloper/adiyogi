<?php
class Chatapi extends Controller {
    private $chatModel;
    private $appointmentModel;

    public function __construct() {
        if(!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }
        $this->chatModel = $this->model('Chat');
        $this->appointmentModel = $this->model('Appointment');
    }

    // Verify user is part of the appointment
    private function verifyAccess($app_id) {
        $app = $this->appointmentModel->getAppointmentById($app_id);
        if(!$app) return false;

        if($_SESSION['user_role'] == 'clinic') {
            // Need to check if this user owns the clinic
            $clinicModel = $this->model('Clinic');
            $clinic = $clinicModel->getClinicByUserId($_SESSION['user_id']);
            if($clinic && $app->clinic_id == $clinic->id) return true;
        } elseif($_SESSION['user_role'] == 'patient') {
            if($app->patient_user_id == $_SESSION['user_id']) return true;
        }

        return false;
    }

    // AJAX Endpoint: Get Messages
    public function get_messages($appointment_id) {
        if(!$this->verifyAccess($appointment_id)) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }

        $last_id = isset($_GET['last_id']) ? (int)$_GET['last_id'] : 0;
        $messages = $this->chatModel->getMessages($appointment_id, $last_id);

        $formatted = [];
        foreach($messages as $msg) {
            $formatted[] = [
                'id' => $msg->id,
                'is_mine' => ($msg->sender_id == $_SESSION['user_id']),
                'sender_name' => ($msg->role == 'clinic') ? 'Doctor' : $msg->first_name,
                'message' => htmlspecialchars($msg->message),
                'file_path' => $msg->file_path ? htmlspecialchars($msg->file_path) : null,
                'time' => date('g:i a', strtotime($msg->created_at))
            ];
        }

        header('Content-Type: application/json');
        echo json_encode(['messages' => $formatted]);
    }

    // AJAX Endpoint: Send Message
    public function send_message() {
        if($_SERVER['REQUEST_METHOD'] != 'POST') exit;

        // Verify CSRF Token for state-changing operations
        $headers = getallheaders();
        $csrf_token = $_POST['csrf_token'] ?? $headers['X-CSRF-Token'] ?? '';
        if (!$this->validateCsrfToken($csrf_token)) {
            http_response_code(403);
            echo json_encode(['error' => 'CSRF token validation failed']);
            exit;
        }

        $app_id = $_POST['appointment_id'] ?? 0;

        if(!$this->verifyAccess($app_id)) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }

        $message = trim($_POST['message'] ?? '');
        $file_path = null;

        // Handle File Upload Securely
        if(isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'pdf'];
            $filename = $_FILES['file']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if(in_array($ext, $allowed) && $_FILES['file']['size'] < 5000000) { // 5MB limit
                $new_filename = uniqid('chat_') . '.' . $ext;
                $upload_path = APP_ROOT . '/assets/uploads/chat/' . $new_filename;

                if(move_uploaded_file($_FILES['file']['tmp_name'], $upload_path)) {
                    $file_path = $new_filename;
                }
            }
        }

        if(empty($message) && empty($file_path)) {
            http_response_code(400);
            echo json_encode(['error' => 'Empty message']);
            exit;
        }

        $data = [
            'appointment_id' => $app_id,
            'sender_id' => $_SESSION['user_id'],
            'message' => $message,
            'file_path' => $file_path
        ];

        if($this->chatModel->saveMessage($data)) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to save message']);
        }
    }
}
