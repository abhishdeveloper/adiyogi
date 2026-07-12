<?php
class PatientDashboard extends Controller {
    private $userModel;
    private $clinicModel;
    private $appointmentModel;
    private $settingModel;
    private $db;

    public function __construct() {
        if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'patient') {
            header('location: /auth/login');
            exit;
        }

        $this->userModel = $this->model('User');
        $this->clinicModel = $this->model('Clinic');
        $this->appointmentModel = $this->model('Appointment');
        $this->settingModel = $this->model('Setting');
        $this->db = new Database();
    }

    // Dashboard Overview (Shows linked clinics and appointments)
    public function index() {
        // Get linked clinics
        $this->db->query("SELECT c.* FROM clinics c JOIN patient_clinic_links l ON c.id = l.clinic_id WHERE l.patient_user_id = :uid");
        $this->db->bind(':uid', $_SESSION['user_id']);
        $linked_clinics = $this->db->resultSet();

        $appointments = $this->appointmentModel->getPatientAppointments($_SESSION['user_id']);

        $data = [
            'title' => 'Patient Dashboard',
            'clinics' => $linked_clinics,
            'appointments' => $appointments,
            'success_msg' => isset($_GET['success']) ? 'Action completed successfully.' : '',
            'error_msg' => isset($_GET['error']) ? 'An error occurred.' : ''
        ];

        $this->view('patient/index', $data);
    }

    // Link a new clinic using their unique code
    public function link_clinic() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!$this->validateCsrfToken($_POST['csrf_token'])) {
                die("CSRF token validation failed.");
            }

            $code = trim($_POST['clinic_code']);

            // Find clinic by code
            $this->db->query("SELECT id FROM clinics WHERE unique_code = :code");
            $this->db->bind(':code', $code);
            $clinic = $this->db->single();

            if($clinic) {
                // Check if already linked
                $this->db->query("SELECT id FROM patient_clinic_links WHERE patient_user_id = :uid AND clinic_id = :cid");
                $this->db->bind(':uid', $_SESSION['user_id']);
                $this->db->bind(':cid', $clinic->id);
                if($this->db->rowCount() == 0) {
                    // Create link
                    $this->db->query("INSERT INTO patient_clinic_links (patient_user_id, clinic_id) VALUES (:uid, :cid)");
                    $this->db->bind(':uid', $_SESSION['user_id']);
                    $this->db->bind(':cid', $clinic->id);
                    $this->db->execute();
                    header('location: /patientdashboard/index?success=linked');
                    exit;
                }
            }
            header('location: /patientdashboard/index?error=notfound');
            exit;
        }
    }

    // Book appointment page
    public function book($clinic_id = null) {
        if(!$clinic_id) {
            header('location: /patientdashboard/index');
            exit;
        }

        $this->db->query("SELECT * FROM clinics WHERE id = :id");
        $this->db->bind(':id', $clinic_id);
        $clinic = $this->db->single();

        if(!$clinic) {
            die("Clinic not found.");
        }

        // Determine Payment Options
        // If clinic is premium, they can override global payment settings.
        // For simplicity, we'll check global setting first, then override if premium.
        $global_pref = $this->settingModel->getSetting('global_payment_preference');

        $payment_preference = 'both'; // Default
        if ($clinic->is_premium) {
            $payment_preference = $clinic->payment_preference;
            $rzp_key = $clinic->razorpay_key;
            $rzp_secret = $clinic->razorpay_secret;
        } else {
            $payment_preference = $global_pref;
            $rzp_key = $this->settingModel->getSetting('razorpay_key_id');
            $rzp_secret = $this->settingModel->getSetting('razorpay_key_secret');
        }

        // If online is required but no keys are set, fallback to offline
        if(empty($rzp_key) || empty($rzp_secret)) {
            $payment_preference = 'offline';
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!$this->validateCsrfToken($_POST['csrf_token'])) {
                die("CSRF token validation failed.");
            }

            $datetime = trim($_POST['appointment_datetime']);
            $method = trim($_POST['payment_method']); // 'online' or 'offline'
            $amount = 500.00; // Fixed consultation fee for demo purposes

            // Validate method against preference
            if($payment_preference == 'online' && $method != 'online') $method = 'online';
            if($payment_preference == 'offline' && $method != 'offline') $method = 'offline';

            $appData = [
                'clinic_id' => $clinic->id,
                'patient_user_id' => $_SESSION['user_id'],
                'appointment_datetime' => $datetime,
                'status' => 'pending',
                'payment_method' => $method,
                'amount' => $amount
            ];

            $app_id = $this->appointmentModel->createAppointment($appData);

            if($app_id) {
                // Email patient about appointment creation
                $mailer = new Mailer();
                $subject = "Appointment Booked - " . $clinic->clinic_name;
                $msg = "<p>Hello " . $_SESSION['user_name'] . ",</p>";
                $msg .= "<p>Your appointment at " . $clinic->clinic_name . " has been booked for " . date('M j, Y h:i A', strtotime($datetime)) . ".</p>";
                $msg .= "<p>Payment Method: " . ucfirst($method) . "</p>";
                @$mailer->send($_SESSION['user_email'], $subject, $msg);

                if($method == 'online') {
                    // Integrate Razorpay Order API using cURL
                    $orderData = [
                        'receipt' => 'rcptid_' . $app_id,
                        'amount' => $amount * 100, // in paise
                        'currency' => 'INR'
                    ];

                    $ch = curl_init('https://api.razorpay.com/v1/orders');
                    curl_setopt($ch, CURLOPT_USERPWD, $rzp_key . ':' . $rzp_secret);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderData));
                    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

                    $response = curl_exec($ch);
                    curl_close($ch);

                    $rzpOrder = json_decode($response);

                    if(isset($rzpOrder->id)) {
                        $this->appointmentModel->updateRazorpayOrder($app_id, $rzpOrder->id);

                        // Pass data to payment view
                        $data = [
                            'clinic' => $clinic,
                            'appointment_id' => $app_id,
                            'razorpay_order_id' => $rzpOrder->id,
                            'amount' => $amount,
                            'key_id' => $rzp_key,
                            'user_name' => $_SESSION['user_name'],
                            'user_email' => $_SESSION['user_email']
                        ];
                        $this->view('patient/pay', $data);
                        return;
                    } else {
                        // API failure fallback
                        header('location: /patientdashboard/index?error=paymentapi');
                        exit;
                    }
                } else {
                    // Offline payment
                    header('location: /patientdashboard/index?success=booked');
                    exit;
                }
            } else {
                header('location: /patientdashboard/index?error=createfailed');
                exit;
            }
        }

        $data = [
            'clinic' => $clinic,
            'payment_pref' => $payment_preference
        ];

        $this->view('patient/book', $data);
    }

    // Razorpay Callback Verification
    public function verify_payment() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            $app_id = $_POST['appointment_id'];
            $rzp_payment_id = $_POST['razorpay_payment_id'];
            $rzp_order_id = $_POST['razorpay_order_id'];
            $rzp_signature = $_POST['razorpay_signature'];

            // Get Clinic to find which secret to use to verify
            $this->db->query("SELECT c.* FROM clinics c JOIN appointments a ON a.clinic_id = c.id WHERE a.id = :id");
            $this->db->bind(':id', $app_id);
            $clinic = $this->db->single();

            $secret = $this->settingModel->getSetting('razorpay_key_secret');
            if($clinic && $clinic->is_premium && !empty($clinic->razorpay_secret)) {
                $secret = $clinic->razorpay_secret;
            }

            // Verify Signature
            $generated_signature = hash_hmac('sha256', $rzp_order_id . "|" . $rzp_payment_id, $secret);

            if (hash_equals($generated_signature, $rzp_signature)) {
                $this->appointmentModel->confirmPayment($app_id, $rzp_payment_id);
                header('location: /patientdashboard/index?success=paid');
            } else {
                header('location: /patientdashboard/index?error=paymentfailed');
            }
        }
    }

    // Medical Records & Lab Reports
    public function records() {
        $medicalRecordModel = $this->model('MedicalRecord');
        $prescriptionModel = $this->model('Prescription');

        // Handle file upload
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!$this->validateCsrfToken($_POST['csrf_token'])) {
                die("CSRF token validation failed.");
            }

            if(isset($_POST['action']) && $_POST['action'] == 'upload') {
                $file_path = null;
                if(isset($_FILES['report_file']) && $_FILES['report_file']['error'] == 0) {
                    $allowed = ['pdf', 'jpg', 'jpeg', 'png'];
                    $filename = $_FILES['report_file']['name'];
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                    if(in_array($ext, $allowed) && $_FILES['report_file']['size'] < 10000000) { // 10MB limit
                        $new_filename = uniqid('report_') . '.' . $ext;
                        $upload_path = APP_ROOT . '/assets/uploads/reports/' . $new_filename;
                        if(move_uploaded_file($_FILES['report_file']['tmp_name'], $upload_path)) {
                            $file_path = $new_filename;
                        }
                    }
                }

                if($file_path) {
                    $data = [
                        'patient_user_id' => $_SESSION['user_id'],
                        'title' => trim($_POST['title']),
                        'file_path' => $file_path,
                        'report_date' => trim($_POST['report_date']),
                        'notes' => trim($_POST['notes'] ?? '')
                    ];
                    $medicalRecordModel->addReport($data);
                    header('location: /patientdashboard/records?success=uploaded');
                    die();
                } else {
                    header('location: /patientdashboard/records?error=uploadfailed');
                    die();
                }
            } elseif(isset($_POST['action']) && $_POST['action'] == 'delete') {
                $medicalRecordModel->deleteReport($_POST['report_id'], $_SESSION['user_id']);
                header('location: /patientdashboard/records?success=deleted');
                die();
            }
        }

        $prescriptions = $prescriptionModel->getPatientPrescriptions($_SESSION['user_id']);
        $reports = $medicalRecordModel->getPatientReports($_SESSION['user_id']);

        $data = [
            'prescriptions' => $prescriptions,
            'reports' => $reports,
            'success_msg' => isset($_GET['success']) ? 'Action completed successfully.' : '',
            'error_msg' => isset($_GET['error']) ? 'An error occurred during file upload.' : ''
        ];

        $this->view('patient/records', $data);
    }


    // Telemedicine Video Consultation (Patient)
    public function telemedicine($appointment_id) {
        $appointment = $this->appointmentModel->getAppointmentById($appointment_id);

        if(!$appointment || $appointment->patient_user_id != $_SESSION['user_id'] || $appointment->consultation_type != 'video') {
            die("Invalid video consultation session.");
        }

        $data = [
            'appointment' => $appointment
        ];

        $this->view('patient/telemedicine', $data);
    }
}