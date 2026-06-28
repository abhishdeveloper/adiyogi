<?php
class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Register a new user
    public function register($data) {
        $this->db->query('INSERT INTO users (role, email, password, first_name, last_name, is_active) VALUES (:role, :email, :password, :first_name, :last_name, :is_active)');

        $this->db->bind(':role', $data['role']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name']);

        // Clinics/Doctors are inactive until verified by superadmin
        // Patients are active immediately
        $isActive = ($data['role'] == 'patient') ? 1 : 0;
        $this->db->bind(':is_active', $isActive);

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    // Register or login user via Google Auth
    public function handleGoogleUser($googleInfo, $role = 'patient') {
        // Check if email already exists
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $googleInfo['email']);
        $row = $this->db->single();

        if ($this->db->rowCount() > 0) {
            // User exists, update google_id if not set
            if(empty($row->google_id)) {
                $this->db->query('UPDATE users SET google_id = :google_id WHERE id = :id');
                $this->db->bind(':google_id', $googleInfo['id']);
                $this->db->bind(':id', $row->id);
                $this->db->execute();
            }
            return $row;
        } else {
            // Register new user via Google
            $this->db->query('INSERT INTO users (role, email, google_id, first_name, last_name, is_active) VALUES (:role, :email, :google_id, :first_name, :last_name, :is_active)');
            $this->db->bind(':role', $role);
            $this->db->bind(':email', $googleInfo['email']);
            $this->db->bind(':google_id', $googleInfo['id']);
            $this->db->bind(':first_name', $googleInfo['given_name']);
            $this->db->bind(':last_name', $googleInfo['family_name']);

            $isActive = ($role == 'patient') ? 1 : 0;
            $this->db->bind(':is_active', $isActive);

            if ($this->db->execute()) {
                $newId = $this->db->lastInsertId();
                return $this->getUserById($newId);
            }
            return false;
        }
    }

    // Find user by email
    public function findUserByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        $row = $this->db->single();

        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    // Login user
    public function login($email, $password) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();
        if(!$row) return false;

        $hashed_password = $row->password;
        if (password_verify($password, $hashed_password)) {
            return $row;
        } else {
            return false;
        }
    }

    // Get user by ID
    public function getUserById($id) {
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);

        return $this->db->single();
    }
}
