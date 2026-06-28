<?php
class Auth extends Controller {
    private $userModel;
    private $clinicModel;
    private $settingModel;

    public function __construct() {
        $this->userModel = $this->model('User');
        $this->clinicModel = $this->model('Clinic');
        $this->settingModel = $this->model('Setting');
    }

    // Load Login Page
    public function login() {
        // Check for POST
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!$this->validateCsrfToken($_POST['csrf_token'])) {
                die("CSRF token validation failed.");
            }

            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => ''
            ];

            // Validate Email
            if(empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            }

            // Validate Password
            if(empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            }

            // Check for user/email
            if($this->userModel->findUserByEmail($data['email'])) {
                // User found
            } else {
                $data['email_err'] = 'No user found';
            }

            // Make sure errors are empty
            if(empty($data['email_err']) && empty($data['password_err'])) {
                // Validated
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);

                if($loggedInUser) {
                    if($loggedInUser->is_active == 0) {
                        $data['email_err'] = 'Your account is pending verification by an administrator.';
                        $this->view('auth/login', $data);
                        return;
                    }
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Password incorrect';
                    $this->view('auth/login', $data);
                }
            } else {
                // Load view with errors
                $this->view('auth/login', $data);
            }
        } else {
            // Init data
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => ''
            ];
            $this->view('auth/login', $data);
        }
    }

    // Generic registration dispatcher
    public function register() {
        $this->view('auth/register_choice');
    }

    // Patient Registration
    public function patient_register() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!$this->validateCsrfToken($_POST['csrf_token'])) {
                die("CSRF token validation failed.");
            }

            $_POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            $data = [
                'role' => 'patient',
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'first_name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Validation...
            if(empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            } else {
                if($this->userModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'Email is already taken';
                }
            }

            if(empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            } elseif(strlen($data['password']) < 6) {
                $data['password_err'] = 'Password must be at least 6 characters';
            }

            if(empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Please confirm password';
            } else {
                if($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Passwords do not match';
                }
            }

            if(empty($data['email_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
                $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
                if($this->userModel->register($data)) {
                    header('location: /auth/login');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('auth/patient_register', $data);
            }
        } else {
            $data = [
                'first_name' => '', 'last_name' => '', 'email' => '',
                'password' => '', 'confirm_password' => '',
                'first_name_err' => '', 'email_err' => '',
                'password_err' => '', 'confirm_password_err' => ''
            ];
            $this->view('auth/patient_register', $data);
        }
    }

    // Clinic Registration
    public function clinic_register() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!$this->validateCsrfToken($_POST['csrf_token'])) {
                die("CSRF token validation failed.");
            }

            $_POST = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            $data = [
                'role' => 'clinic',
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'clinic_name' => trim($_POST['clinic_name']),
                'custom_code' => trim($_POST['custom_code']),
                'phone' => trim($_POST['phone']),
                'address' => trim($_POST['address']),
                'city' => trim($_POST['city']),
                'state' => trim($_POST['state']),
                'zip' => trim($_POST['zip']),
                'license_number' => trim($_POST['license_number']),
                'specialty' => trim($_POST['specialty']),
                'email_err' => '', 'password_err' => '', 'confirm_password_err' => '',
                'clinic_name_err' => '', 'code_err' => '',
                'google_id' => isset($_POST['google_id']) ? $_POST['google_id'] : null
            ];

            // Validate User Info
            if(empty($data['email'])) { $data['email_err'] = 'Please enter email'; }
            elseif($this->userModel->findUserByEmail($data['email'])) { $data['email_err'] = 'Email is already taken'; }

            if(empty($data['password'])) { $data['password_err'] = 'Please enter password'; }
            if(empty($data['google_id']) && $data['password'] != $data['confirm_password']) { $data['confirm_password_err'] = 'Passwords do not match'; }
            if(empty($data['clinic_name'])) { $data['clinic_name_err'] = 'Please enter clinic name'; }

            // Handle Unique Code and URL Slug
            $unique_code = '';
            $url_slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $data['clinic_name'])));

            if(!empty($data['custom_code'])) {
                if($this->clinicModel->codeExists($data['custom_code'])) {
                    $data['code_err'] = 'This code is already taken.';
                } else {
                    $unique_code = $data['custom_code'];
                }
            } else {
                $unique_code = $this->clinicModel->generateUniqueCode();
            }

            // Ensure URL slug is unique
            $base_slug = $url_slug;
            $counter = 1;
            while($this->clinicModel->slugExists($url_slug)) {
                $url_slug = $base_slug . '-' . $counter;
                $counter++;
            }

            if(empty($data['email_err']) && empty($data['password_err']) && empty($data['confirm_password_err']) && empty($data['clinic_name_err']) && empty($data['code_err'])) {
                $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);

                // Register User first
                if (isset($data['google_id'])) {
                    $userToRegister = [
                        'email' => $data['email'],
                        'id' => $data['google_id'],
                        'given_name' => $data['first_name'],
                        'family_name' => $data['last_name']
                    ];
                    $user = $this->userModel->handleGoogleUser($userToRegister, 'clinic');
                    $userId = $user ? $user->id : false;
                    unset($_SESSION['pending_clinic_google_auth']);
                } else {
                    $userId = $this->userModel->register($data);
                }

                if($userId) {
                    $clinicData = [
                        'user_id' => $userId,
                        'clinic_name' => $data['clinic_name'],
                        'unique_code' => $unique_code,
                        'url_slug' => $url_slug,
                        'phone' => $data['phone'],
                        'address' => $data['address'],
                        'city' => $data['city'],
                        'state' => $data['state'],
                        'zip' => $data['zip'],
                        'license_number' => $data['license_number'],
                        'specialty' => $data['specialty']
                    ];

                    if($this->clinicModel->register($clinicData)) {
                        header('location: /auth/login?registered=clinic');
                    } else {
                        die('Failed to register clinic details.');
                    }
                } else {
                    die('Failed to register user.');
                }
            } else {
                $this->view('auth/clinic_register', $data);
            }
        } else {
            $data = [
                'first_name' => '', 'last_name' => '', 'email' => '', 'password' => '', 'confirm_password' => '',
                'clinic_name' => '', 'custom_code' => '', 'phone' => '', 'address' => '', 'city' => '', 'state' => '', 'zip' => '',
                'license_number' => '', 'specialty' => '', 'email_err' => '', 'password_err' => '', 'confirm_password_err' => '', 'clinic_name_err' => '', 'code_err' => ''
            ];

            // If forwarded from google auth, pre-fill data
            if (isset($_SESSION['pending_clinic_google_auth'])) {
                $googleUser = $_SESSION['pending_clinic_google_auth'];
                $data['email'] = $googleUser['email'];
                $data['first_name'] = $googleUser['given_name'] ?? '';
                $data['last_name'] = $googleUser['family_name'] ?? '';
                $data['google_id'] = $googleUser['id'];
            }

            $this->view('auth/clinic_register', $data);
        }
    }

    // Google OAuth Login Flow
    public function google() {
        $client_id = $this->settingModel->getSetting('google_client_id');
        $redirect_uri = APP_URL . '/auth/google_callback';

        if(empty($client_id)) {
            die("Google Auth is not configured by the administrator yet.");
        }

        $role = isset($_GET['role']) && $_GET['role'] == 'clinic' ? 'clinic' : 'patient';
        // Generate a secure state token combining CSRF and Role to prevent manipulation
        $state = bin2hex(random_bytes(16)) . '_' . $role;
        $_SESSION['oauth_state'] = $state;

        $url = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
            'client_id' => $client_id,
            'redirect_uri' => $redirect_uri,
            'response_type' => 'code',
            'scope' => 'email profile',
            'access_type' => 'online',
            'state' => $state
        ]);

        header("Location: $url");
        exit;
    }

    public function google_callback() {
        if (!isset($_GET['code']) || !isset($_GET['state']) || $_GET['state'] !== $_SESSION['oauth_state']) {
            header('location: /auth/login');
            exit;
        }

        $state_parts = explode('_', $_GET['state']);
        $role = isset($state_parts[1]) && $state_parts[1] == 'clinic' ? 'clinic' : 'patient';

        $client_id = $this->settingModel->getSetting('google_client_id');
        $client_secret = $this->settingModel->getSetting('google_client_secret');
        $redirect_uri = APP_URL . '/auth/google_callback';

        // Exchange code for token via cURL
        $ch = curl_init('https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'redirect_uri' => $redirect_uri,
            'grant_type' => 'authorization_code',
            'code' => $_GET['code']
        ]));
        $response = curl_exec($ch);
        curl_close($ch);

        $tokenData = json_decode($response, true);

        if(isset($tokenData['access_token'])) {
            // Get user info
            $ch = curl_init('https://www.googleapis.com/oauth2/v2/userinfo');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $tokenData['access_token']]);
            $userInfoResponse = curl_exec($ch);
            curl_close($ch);

            $googleUser = json_decode($userInfoResponse, true);

            if(isset($googleUser['email'])) {
                // If it is a clinic trying to register, we must divert them to the clinic details form first
                if ($role == 'clinic' && !$this->userModel->findUserByEmail($googleUser['email'])) {
                    // Store google info in session to carry over to clinic_register
                    $_SESSION['pending_clinic_google_auth'] = $googleUser;
                    header('location: /auth/clinic_register');
                    exit;
                }

                // Normal login / Patient registration
                $user = $this->userModel->handleGoogleUser($googleUser, $role);

                if($user->is_active == 0) {
                    die("Your account is pending verification by an administrator.");
                }

                $this->createUserSession($user);
            }
        }

        // Fallback on failure
        header('location: /auth/login');
    }

    public function createUserSession($user) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_role'] = $user->role;
        $_SESSION['user_name'] = $user->first_name . ' ' . $user->last_name;
        header('location: /dashboard');
    }

    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_role']);
        unset($_SESSION['user_name']);
        session_destroy();
        header('location: /auth/login');
    }
}
