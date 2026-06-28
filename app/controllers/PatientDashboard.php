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
}
