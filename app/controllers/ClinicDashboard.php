<?php
class ClinicDashboard extends Controller {
    private $clinicModel;
    private $appointmentModel;
    private $userModel;

    public function __construct() {
        if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'clinic') {
            header('location: /auth/login');
            exit;
        }

        $this->clinicModel = $this->model('Clinic');
        $this->appointmentModel = $this->model('Appointment');
        $this->userModel = $this->model('User');
    }

    // Dashboard Overview
    public function index() {
        $clinic = $this->clinicModel->getClinicByUserId($_SESSION['user_id']);

        $data = [
            'title' => 'Clinic Dashboard',
            'clinic' => $clinic,
            'appointments' => $this->appointmentModel->getClinicAppointments($clinic->id),
            'earnings' => $this->appointmentModel->getClinicEarnings($clinic->id)
        ];

        $this->view('clinic/index', $data);
    }

    // Update Digital Profile
    public function profile() {
        $clinic = $this->clinicModel->getClinicByUserId($_SESSION['user_id']);

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!$this->validateCsrfToken($_POST['csrf_token'])) {
                die("CSRF token validation failed.");
            }

            $_POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            // Handle Profile Image Upload securely
            $profile_image = $clinic->profile_image;
            if(isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $filename = $_FILES['profile_image']['name'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                if(in_array($ext, $allowed)) {
                    $new_filename = uniqid('profile_') . '.' . $ext;
                    $upload_path = APP_ROOT . '/assets/uploads/profiles/' . $new_filename;
                    if(move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_path)) {
                        $profile_image = $new_filename;
                    }
                }
            }

            $social_links = [
                'instagram' => trim($_POST['instagram'] ?? ''),
                'facebook' => trim($_POST['facebook'] ?? ''),
                'linkedin' => trim($_POST['linkedin'] ?? ''),
                'website' => trim($_POST['website'] ?? '')
            ];

            $updateData = [
                'user_id' => $_SESSION['user_id'],
                'bio' => trim($_POST['bio']),
                'degrees' => trim($_POST['degrees']),
                'achievements' => trim($_POST['achievements']),
                'social_links' => $social_links,
                'theme_preference' => trim($_POST['theme_preference']),
                'profile_image' => $profile_image
            ];

            if($this->clinicModel->updateProfile($updateData)) {
                $data['success_msg'] = 'Profile updated successfully.';
            } else {
                $data['error_msg'] = 'Something went wrong.';
            }

            $clinic = $this->clinicModel->getClinicByUserId($_SESSION['user_id']);
        }

        $socials = json_decode($clinic->social_links, true) ?: ['instagram'=>'', 'facebook'=>'', 'linkedin'=>'', 'website'=>''];

        $data = [
            'clinic' => $clinic,
            'socials' => $socials,
            'success_msg' => $data['success_msg'] ?? '',
            'error_msg' => $data['error_msg'] ?? ''
        ];

        $this->view('clinic/profile', $data);
    }

    // Book new appointment & create patient
    public function book() {
        $clinic = $this->clinicModel->getClinicByUserId($_SESSION['user_id']);

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!$this->validateCsrfToken($_POST['csrf_token'])) {
                die("CSRF token validation failed.");
            }

            $_POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            $data = [
                'clinic' => $clinic,
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'email' => trim($_POST['email']),
                'appointment_datetime' => trim($_POST['appointment_datetime']),
                'amount' => trim($_POST['amount']),
                'error_msg' => ''
            ];

            if(empty($data['first_name']) || empty($data['email']) || empty($data['appointment_datetime'])) {
                $data['error_msg'] = 'Please fill in all required fields.';
            } else {
                // Check if patient exists, if not, create them
                $patient_id = null;
                if($this->userModel->findUserByEmail($data['email'])) {
                    // Get existing user id (simplified for this demo, assumes user is patient)
                    // A robust system would load the full user object here
                    $this->db = new Database();
                    $this->db->query("SELECT id FROM users WHERE email = :email");
                    $this->db->bind(':email', $data['email']);
                    $patient = $this->db->single();
                    $patient_id = $patient->id;
                } else {
                    // Create basic patient profile
                    $default_password = bin2hex(random_bytes(8));
                    $patient_id = $this->userModel->createPatientProfile($data['first_name'], $data['last_name'], $data['email'], $default_password);
                }

                if($patient_id) {
                    $appointmentData = [
                        'clinic_id' => $clinic->id,
                        'patient_user_id' => $patient_id,
                        'appointment_datetime' => $data['appointment_datetime'],
                        'status' => 'confirmed', // Doctor booked it, so auto-confirm
                        'payment_method' => 'offline',
                        'amount' => $data['amount'] ?: 0.00
                    ];

                    if($this->appointmentModel->createAppointment($appointmentData)) {
                        header('location: /clinicdashboard/index?success=booked');
                        exit;
                    } else {
                        $data['error_msg'] = 'Failed to create appointment record.';
                    }
                } else {
                    $data['error_msg'] = 'Failed to create patient profile.';
                }
            }

            $this->view('clinic/book', $data);

        } else {
            $data = [
                'clinic' => $clinic,
                'first_name' => '', 'last_name' => '', 'email' => '', 'appointment_datetime' => '', 'amount' => '',
                'error_msg' => ''
            ];
            $this->view('clinic/book', $data);
        }
    }
}
