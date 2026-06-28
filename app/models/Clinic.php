<?php
class Clinic {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Register a new clinic
    public function register($data) {
        $this->db->query('INSERT INTO clinics (user_id, clinic_name, unique_code, url_slug, phone, address, city, state, zip, license_number, specialty) VALUES (:user_id, :clinic_name, :unique_code, :url_slug, :phone, :address, :city, :state, :zip, :license_number, :specialty)');

        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':clinic_name', $data['clinic_name']);
        $this->db->bind(':unique_code', $data['unique_code']);
        $this->db->bind(':url_slug', $data['url_slug']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':city', $data['city']);
        $this->db->bind(':state', $data['state']);
        $this->db->bind(':zip', $data['zip']);
        $this->db->bind(':license_number', $data['license_number']);
        $this->db->bind(':specialty', $data['specialty']);

        return $this->db->execute();
    }

    // Check if unique code exists
    public function codeExists($code) {
        $this->db->query('SELECT id FROM clinics WHERE unique_code = :code');
        $this->db->bind(':code', $code);
        $this->db->single();
        return $this->db->rowCount() > 0;
    }

    // Check if URL slug exists
    public function slugExists($slug) {
        $this->db->query('SELECT id FROM clinics WHERE url_slug = :slug');
        $this->db->bind(':slug', $slug);
        $this->db->single();
        return $this->db->rowCount() > 0;
    }

    // Generate random unique code
    public function generateUniqueCode() {
        do {
            $code = 'CLINIC-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
        } while ($this->codeExists($code));
        return $code;
    }
}
