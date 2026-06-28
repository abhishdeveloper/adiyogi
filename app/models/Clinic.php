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

    // Get clinic by User ID
    public function getClinicByUserId($user_id) {
        $this->db->query('SELECT * FROM clinics WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);
        return $this->db->single();
    }

    // Get clinic by slug
    public function getClinicBySlug($slug) {
        $this->db->query('SELECT * FROM clinics WHERE url_slug = :slug');
        $this->db->bind(':slug', $slug);
        return $this->db->single();
    }

    // Update clinic profile
    public function updateProfile($data) {
        $this->db->query('UPDATE clinics SET
            bio = :bio,
            degrees = :degrees,
            achievements = :achievements,
            social_links = :social_links,
            theme_preference = :theme_preference,
            profile_image = COALESCE(:profile_image, profile_image)
            WHERE user_id = :user_id');

        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':bio', $data['bio']);
        $this->db->bind(':degrees', $data['degrees']);
        $this->db->bind(':achievements', $data['achievements']);
        $this->db->bind(':social_links', json_encode($data['social_links']));
        $this->db->bind(':theme_preference', $data['theme_preference']);

        if (isset($data['profile_image']) && !empty($data['profile_image'])) {
            $this->db->bind(':profile_image', $data['profile_image']);
        } else {
            $this->db->bind(':profile_image', null, PDO::PARAM_NULL);
        }

        return $this->db->execute();
    }

    // Update premium settings
    public function updatePremiumSettings($data) {
        $this->db->query('UPDATE clinics SET
            razorpay_key = :razorpay_key,
            razorpay_secret = :razorpay_secret,
            payment_preference = :payment_preference
            WHERE user_id = :user_id AND is_premium = 1');

        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':razorpay_key', $data['razorpay_key']);
        $this->db->bind(':razorpay_secret', $data['razorpay_secret']);
        $this->db->bind(':payment_preference', $data['payment_preference']);

        return $this->db->execute();
    }
}
