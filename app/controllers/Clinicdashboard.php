<?php
class Clinicdashboard extends Controller {
    private $clinicModel;
    private $appointmentModel;
    private $userModel;
    private $presavedModel;
    private $prescriptionModel;
    private $staffModel;
    private $db;
    private $settingModel;

    public function __construct() {
        if(!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != 'clinic' && $_SESSION['user_role'] != 'staff')) {
            header('location: /auth/login');
            die();
        }

        $this->clinicModel = $this->model('Clinic');
        $this->appointmentModel = $this->model('Appointment');
        $this->userModel = $this->model('User');
        $this->presavedModel = $this->model('PresavedItem');
        $this->prescriptionModel = $this->model('Prescription');
        $this->staffModel = $this->model('Staff');
    }

    private function getClinicContext() {
        if($_SESSION['user_role'] == 'staff') {
            return $this->staffModel->getStaffDetails($_SESSION['user_id']);
        } else {
            $clinic = $this->clinicModel->getClinicByUserId($_SESSION['user_id']);
            $clinic->staff_role = 'owner';
            return $clinic;
        }
    }

    private function requireOwner() {
        if($_SESSION['user_role'] == 'staff') {
            die("Access Denied: Clinic Owners Only.");
        }
    }

    // Dashboard Overview
    public function index() {
        $clinic = $this->getClinicContext();
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
        $this->requireOwner();
        $clinic = $this->getClinicContext();

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

            if($clinic->is_premium) {
                $updateData['seo_title'] = trim($_POST['seo_title'] ?? '');
                $updateData['seo_description'] = trim($_POST['seo_description'] ?? '');
                $seo_og_image = $clinic->seo_og_image;
                if(isset($_FILES['seo_og_image']) && $_FILES['seo_og_image']['error'] == 0) {
                    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
                    $filename = $_FILES['seo_og_image']['name'];
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    if(in_array($ext, $allowed)) {
                        $new_filename = uniqid('og_') . '.' . $ext;
                        $upload_path = APP_ROOT . '/assets/uploads/profiles/' . $new_filename;
                        if(move_uploaded_file($_FILES['seo_og_image']['tmp_name'], $upload_path)) {
                            $seo_og_image = $new_filename;
                        }
                    }
                }
                $updateData['seo_og_image'] = $seo_og_image;
            }

            if($this->clinicModel->updateProfile($updateData)) {
                $data['success_msg'] = 'Profile updated successfully.';
            } else {
                $data['error_msg'] = 'Something went wrong.';
            }

            $clinic = $this->getClinicContext();
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
        $clinic = $this->getClinicContext();

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
                        'amount' => $data['amount'] ?: 0.00,
                        'consultation_type' => $data['consultation_type']
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
        $clinic = $this->getClinicContext();

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
        $clinic = $this->getClinicContext();
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
        $clinic = $this->getClinicContext();
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


    public function staff() {
        $this->requireOwner();
        $clinic = $this->getClinicContext();

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!$this->validateCsrfToken($_POST['csrf_token'])) {
                die("CSRF token validation failed.");
            }

            if(isset($_POST['action']) && $_POST['action'] == 'add') {
                $data = [
                    'clinic_id' => $clinic->id,
                    'first_name' => trim($_POST['first_name']),
                    'last_name' => trim($_POST['last_name']),
                    'email' => trim($_POST['email']),
                    'password' => trim($_POST['password']),
                    'staff_role' => trim($_POST['staff_role'])
                ];

                if($this->userModel->findUserByEmail($data['email'])) {
                    header('location: /clinicdashboard/staff?error=emailexists');
                    die();
                }

                if($this->staffModel->addStaff($data)) {
                    header('location: /clinicdashboard/staff?success=added');
                } else {
                    header('location: /clinicdashboard/staff?error=failed');
                }
                die();
            } elseif(isset($_POST['action']) && $_POST['action'] == 'delete') {
                $this->staffModel->removeStaff($_POST['staff_user_id'], $clinic->id);
                header('location: /clinicdashboard/staff?success=removed');
                die();
            }
        }

        $staff_members = $this->staffModel->getClinicStaff($clinic->id);

        $data = [
            'clinic' => $clinic,
            'staff' => $staff_members,
            'success_msg' => isset($_GET['success']) ? 'Action completed successfully.' : '',
            'error_msg' => isset($_GET['error']) ? 'An error occurred (email might be in use).' : ''
        ];

        $this->view('clinic/staff', $data);
    }

    public function telemedicine($appointment_id) {
        $clinic = $this->getClinicContext();
        $appointment = $this->appointmentModel->getAppointmentById($appointment_id);

        if(!$appointment || $appointment->clinic_id != $clinic->id || $appointment->consultation_type != 'video') {
            die("Invalid video consultation session.");
        }

        $data = [
            'clinic' => $clinic,
            'appointment' => $appointment
        ];

        $this->view('clinic/telemedicine', $data);
    }


    // --- FullCalendar Integration --- //

    public function calendar() {
        $clinic = $this->getClinicContext();
        $data = ['clinic' => $clinic];
        $this->view('clinic/calendar', $data);
    }

    public function api_events() {
        if($_SERVER['REQUEST_METHOD'] != 'GET') die();

        $clinic = $this->getClinicContext();
        $start = $_GET['start'] ?? date('Y-m-01');
        $end = $_GET['end'] ?? date('Y-m-t');

        $appointments = $this->appointmentModel->getClinicAppointmentsByRange($clinic->id, $start, $end);
        $blockModel = $this->model('ClinicBlock');
        $blocks = $blockModel->getBlockedTimes($clinic->id, $start, $end);

        $events = [];
        foreach($appointments as $app) {
            $events[] = [
                'id' => 'app_' . $app->id,
                'title' => $app->first_name . ' ' . $app->last_name,
                'start' => $app->appointment_datetime,
                'end' => date('Y-m-d\TH:i:s', strtotime($app->appointment_datetime) + 1800),
                'backgroundColor' => $app->status == 'confirmed' ? '#10b981' : '#f59e0b',
                'borderColor' => 'transparent',
                'extendedProps' => ['type' => 'appointment']
            ];
        }

        foreach($blocks as $block) {
            $events[] = [
                'id' => 'blk_' . $block->id,
                'title' => $block->reason,
                'start' => $block->start_datetime,
                'end' => $block->end_datetime,
                'backgroundColor' => '#ef4444',
                'borderColor' => 'transparent',
                'extendedProps' => ['type' => 'block']
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($events);
    }

    public function api_update_event() {
        if($_SERVER['REQUEST_METHOD'] != 'POST') die();

        $headers = getallheaders();
        $csrf_token = $_POST['csrf_token'] ?? $headers['X-CSRF-Token'] ?? '';
        if (!$this->validateCsrfToken($csrf_token)) {
            http_response_code(403);
            echo json_encode(['error' => 'CSRF validation failed']);
            die();
        }

        $clinic = $this->getClinicContext();
        $event_id = $_POST['event_id'] ?? '';
        $new_start = $_POST['new_start'] ?? '';

        if(strpos($event_id, 'app_') === 0) {
            $app_id = str_replace('app_', '', $event_id);
            $new_datetime = date('Y-m-d H:i:s', strtotime($new_start));

            if($this->appointmentModel->updateAppointmentTime($app_id, $clinic->id, $new_datetime)) {
                echo json_encode(['success' => true]);
                die();
            }
        }

        http_response_code(500);
        echo json_encode(['error' => 'Failed to update event']);
    }

    public function api_add_block() {
        if($_SERVER['REQUEST_METHOD'] != 'POST') die();

        $headers = getallheaders();
        $csrf_token = $_POST['csrf_token'] ?? $headers['X-CSRF-Token'] ?? '';
        if (!$this->validateCsrfToken($csrf_token)) {
            http_response_code(403);
            echo json_encode(['error' => 'CSRF validation failed']);
            die();
        }

        $clinic = $this->getClinicContext();
        $start = $_POST['start'] ?? '';
        $end = $_POST['end'] ?? '';
        $reason = $_POST['reason'] ?? 'Personal Time';

        $blockModel = $this->model('ClinicBlock');

        $data = [
            'clinic_id' => $clinic->id,
            'start_datetime' => date('Y-m-d H:i:s', strtotime($start)),
            'end_datetime' => date('Y-m-d H:i:s', strtotime($end)),
            'reason' => $reason
        ];

        if($blockModel->blockTime($data)) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to block time']);
        }
    }


    // Manage Billing & Invoices
    public function billing() {
        $this->requireOwner();
        $clinic = $this->getClinicContext();

        $this->db = new Database();

        // Get Active Subscription
        $this->db->query("SELECT * FROM subscriptions WHERE clinic_id = :cid AND status = 'active' ORDER BY id DESC LIMIT 1");
        $this->db->bind(':cid', $clinic->id);
        $active_sub = $this->db->single();

        // Get Billing History (Invoices)
        $this->db->query("SELECT * FROM invoices WHERE user_id = :uid ORDER BY id DESC");
        $this->db->bind(':uid', $_SESSION['user_id']);
        $invoices = $this->db->resultSet();

        $data = [
            'clinic' => $clinic,
            'active_sub' => $active_sub,
            'invoices' => $invoices
        ];

        $this->view('clinic/billing', $data);
    }

    // Checkout for Subscription Upgrade
    public function pay_subscription() {
        $this->requireOwner();
        $clinic = $this->getClinicContext();

        $plan_id = $_POST['plan_id'] ?? 'premium';
        $amount = 999.00; // Hardcoded for this phase

        // Initiate Razorpay Order using global admin credentials
        $this->settingModel = $this->model('Setting');
        $rzp_key = $this->settingModel->getSetting('razorpay_key_id');
        $rzp_secret = $this->settingModel->getSetting('razorpay_key_secret');

        $orderData = [
            'receipt' => 'sub_rcpt_' . $clinic->id . '_' . time(),
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

        if(!isset($rzpOrder->id)) {
            die("Payment gateway error. Please try again later.");
        }

        $data = [
            'clinic' => $clinic,
            'plan_id' => $plan_id,
            'amount' => $amount,
            'order_id' => $rzpOrder->id,
            'key' => $rzp_key
        ];

        $this->view('clinic/pay_subscription', $data);
    }

    // Verify Subscription Payment
    public function verify_subscription() {
        $this->requireOwner();
        $clinic = $this->getClinicContext();

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $rzp_payment_id = $_POST['razorpay_payment_id'];
            $rzp_order_id = $_POST['razorpay_order_id'];
            $rzp_signature = $_POST['razorpay_signature'];

            $this->settingModel = $this->model('Setting');
            $secret = $this->settingModel->getSetting('razorpay_key_secret');

            $generated_signature = hash_hmac('sha256', $rzp_order_id . "|" . $rzp_payment_id, $secret);

            if (hash_equals($generated_signature, $rzp_signature)) {
                // Update Clinic to Premium
                $this->db = new Database();
                $this->db->query("UPDATE clinics SET is_premium = 1 WHERE id = :id");
                $this->db->bind(':id', $clinic->id);
                $this->db->execute();

                // Record Subscription
                $start_date = date('Y-m-d');
                $end_date = date('Y-m-d', strtotime('+1 month'));
                $this->db->query("INSERT INTO subscriptions (clinic_id, plan_name, amount, start_date, end_date, razorpay_subscription_id) VALUES (:cid, 'premium', 999.00, :start, :end, :payid)");
                $this->db->bind(':cid', $clinic->id);
                $this->db->bind(':start', $start_date);
                $this->db->bind(':end', $end_date);
                $this->db->bind(':payid', $rzp_payment_id);
                $this->db->execute();

                // Record Invoice
                $invoice_no = 'INV-' . strtoupper(uniqid());
                $this->db->query("INSERT INTO invoices (type, reference_id, user_id, amount, invoice_number) VALUES ('subscription', :cid, :uid, 999.00, :inv)");
                $this->db->bind(':cid', $clinic->id);
                $this->db->bind(':uid', $_SESSION['user_id']);
                $this->db->bind(':inv', $invoice_no);
                $this->db->execute();

                header('location: /clinicdashboard/billing?success=upgraded');
                die();
            } else {
                header('location: /clinicdashboard/billing?error=paymentfailed');
                die();
            }
        }
    }

    // Manage Multiple Doctors in a Clinic
    public function doctors() {
        $this->requireOwner();
        $clinic = $this->getClinicContext();

        $this->db = new Database();

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!$this->validateCsrfToken($_POST['csrf_token'])) {
                die("CSRF token validation failed.");
            }

            if(isset($_POST['action']) && $_POST['action'] == 'add') {
                $name = trim($_POST['name']);
                $specialty = trim($_POST['specialty']);
                $qualifications = trim($_POST['qualifications']);
                $bio = trim($_POST['bio']);

                $photo = null;
                if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
                    $allowed = ['jpg', 'jpeg', 'png'];
                    $filename = $_FILES['photo']['name'];
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    if(in_array($ext, $allowed)) {
                        $new_filename = uniqid('doc_') . '.' . $ext;
                        if(move_uploaded_file($_FILES['photo']['tmp_name'], APP_ROOT . '/assets/uploads/profiles/' . $new_filename)) {
                            $photo = $new_filename;
                        }
                    }
                }

                $this->db->query("INSERT INTO clinic_doctors (clinic_id, name, specialty, qualifications, bio, photo) VALUES (:cid, :name, :spec, :qual, :bio, :photo)");
                $this->db->bind(':cid', $clinic->id);
                $this->db->bind(':name', $name);
                $this->db->bind(':spec', $specialty);
                $this->db->bind(':qual', $qualifications);
                $this->db->bind(':bio', $bio);
                $this->db->bind(':photo', $photo);

                if($this->db->execute()) {
                    header('location: /clinicdashboard/doctors?success=added');
                } else {
                    header('location: /clinicdashboard/doctors?error=failed');
                }
                die();

            } elseif(isset($_POST['action']) && $_POST['action'] == 'delete') {
                $doc_id = $_POST['doctor_id'];
                $this->db->query("DELETE FROM clinic_doctors WHERE id = :id AND clinic_id = :cid");
                $this->db->bind(':id', $doc_id);
                $this->db->bind(':cid', $clinic->id);
                $this->db->execute();
                header('location: /clinicdashboard/doctors?success=deleted');
                die();
            }
        }

        $this->db->query("SELECT * FROM clinic_doctors WHERE clinic_id = :cid ORDER BY id DESC");
        $this->db->bind(':cid', $clinic->id);
        $doctors = $this->db->resultSet();

        $data = [
            'clinic' => $clinic,
            'doctors' => $doctors,
            'success_msg' => isset($_GET['success']) ? 'Action completed successfully.' : '',
            'error_msg' => isset($_GET['error']) ? 'An error occurred.' : ''
        ];

        $this->view('clinic/doctors', $data);
    }
}