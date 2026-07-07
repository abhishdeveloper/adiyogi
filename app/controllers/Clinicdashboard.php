<?php
class Clinicdashboard extends Controller {
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
        $this->presavedModel = $this->model('PresavedItem');
        $this->prescriptionModel = $this->model('Prescription');
    }

    // Dashboard Overview
    public function index() {
        $clinic = $this->clinicModel->getClinicByUserId($_SESSION['user_id']);

        $data = [
            'title' => 'Clinic Dashboard',
            'clinic' => $clinic,
            'appointments' => $this->appointmentModel->getClinicAppointments($clinic->id),
            'earnings' => $this->appointmentModel->getClinicEarnings($clinic->id),
            'weekly_stats' => $this->appointmentModel->getWeeklyAppointmentsStats($clinic->id),
            'monthly_earnings' => $this->appointmentModel->getMonthlyEarningsStats($clinic->id),
            'demographics' => $this->appointmentModel->getPatientDemographics($clinic->id)
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
                        // Email patient about appointment creation
                        $mailer = new Mailer();
                        $subject = "Appointment Confirmed - " . $clinic->clinic_name;
                        $msg = "<p>Hello " . $data['first_name'] . ",</p>";
                        $msg .= "<p>Your appointment at " . $clinic->clinic_name . " has been booked for " . date('M j, Y h:i A', strtotime($data['appointment_datetime'])) . ".</p>";
                        @$mailer->send($data['email'], $subject, $msg);

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

    // Manage Presaved Items (Medicines, Diets, Templates)
    public function presaved() {
        $clinic = $this->clinicModel->getClinicByUserId($_SESSION['user_id']);

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!$this->validateCsrfToken($_POST['csrf_token'])) {
                die("CSRF token validation failed.");
            }

            if(isset($_POST['action']) && $_POST['action'] == 'add') {
                $data = [
                    'clinic_id' => $clinic->id,
                    'item_type' => trim($_POST['item_type']),
                    'title' => trim($_POST['title']),
                    'content' => trim($_POST['content'])
                ];
                $this->presavedModel->addItem($data);
            } elseif(isset($_POST['action']) && $_POST['action'] == 'delete') {
                $this->presavedModel->deleteItem($_POST['item_id'], $clinic->id);
            }

            header('location: /clinicdashboard/presaved');
            exit;
        }

        $items = $this->presavedModel->getItemsByClinic($clinic->id);

        $data = [
            'clinic' => $clinic,
            'items' => $items
        ];

        $this->view('clinic/presaved', $data);
    }

    // Attend Appointment (Write Prescription & Chat)
    public function attend($appointment_id) {
        $clinic = $this->clinicModel->getClinicByUserId($_SESSION['user_id']);
        $appointment = $this->appointmentModel->getAppointmentById($appointment_id);

        if(!$appointment || $appointment->clinic_id != $clinic->id) {
            die("Invalid appointment.");
        }

        // Check if prescription already exists
        $existing = $this->prescriptionModel->getByAppointment($appointment_id);
        if($existing) {
            header("location: /clinicdashboard/view_prescription/" . $appointment_id);
            exit;
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!$this->validateCsrfToken($_POST['csrf_token'])) {
                die("CSRF token validation failed.");
            }

            $prescData = [
                'appointment_id' => $appointment_id,
                'clinic_id' => $clinic->id,
                'patient_user_id' => $appointment->patient_user_id,
                'diagnosis' => trim($_POST['diagnosis']),
                'medicines' => trim($_POST['medicines']),
                'diet_instructions' => trim($_POST['diet_instructions']),
                'notes' => trim($_POST['notes'])
            ];

            if($this->prescriptionModel->save($prescData)) {

                // Email patient that prescription is ready
                $mailer = new Mailer();
                $subject = "Your Prescription is Ready - " . $clinic->clinic_name;
                $link = APP_URL . "/patientdashboard/prescription/" . $appointment_id;
                $msg = "<p>Hello " . $appointment->first_name . ",</p>";
                $msg .= "<p>Your prescription is ready. You can view or download it by clicking the link below:</p>";
                $msg .= "<p><a href='$link'>View Prescription</a></p>";
                @$mailer->send($appointment->email, $subject, $msg);

                header("location: /clinicdashboard/view_prescription/" . $appointment_id);
                exit;
            }
        }

        // Load presaved items for quick insertion
        $presaved = [
            'medicines' => $this->presavedModel->getItemsByClinic($clinic->id, 'medicine'),
            'diets' => $this->presavedModel->getItemsByClinic($clinic->id, 'diet'),
            'templates' => $this->presavedModel->getItemsByClinic($clinic->id, 'template')
        ];

        $data = [
            'clinic' => $clinic,
            'appointment' => $appointment,
            'presaved' => $presaved
        ];

        $this->view('clinic/attend', $data);
    }

    public function view_prescription($appointment_id) {
        $clinic = $this->clinicModel->getClinicByUserId($_SESSION['user_id']);
        $prescription = $this->prescriptionModel->getByAppointment($appointment_id);

        if(!$prescription || $prescription->clinic_id != $clinic->id) {
            die("Prescription not found.");
        }

        // Generate sharing links
        $public_link = APP_URL . "/patientdashboard/prescription/" . $appointment_id; // Will implement secure patient view
        $whatsapp_text = urlencode("Hello " . $prescription->patient_first . ", here is your prescription from " . $prescription->clinic_name . ": " . $public_link);
        $telegram_text = urlencode("Hello " . $prescription->patient_first . ", here is your prescription from " . $prescription->clinic_name . ": ") . "&url=" . urlencode($public_link);

        $data = [
            'prescription' => $prescription,
            'whatsapp_link' => "https://wa.me/?text=" . $whatsapp_text,
            'telegram_link' => "https://t.me/share/url?text=" . $telegram_text
        ];

        $this->view('clinic/prescription', $data);
    }
}
