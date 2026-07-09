<?php
class Staff {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Add a new staff member (creates user + links to clinic)
    public function addStaff($data) {
        $this->db->query('INSERT INTO users (role, email, password, first_name, last_name, is_active) VALUES ("staff", :email, :password, :first_name, :last_name, 1)');

        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', password_hash($data['password'], PASSWORD_BCRYPT));
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name']);

        if ($this->db->execute()) {
            $user_id = $this->db->lastInsertId();

            $this->db->query('INSERT INTO clinic_staff (clinic_id, user_id, staff_role) VALUES (:clinic_id, :user_id, :staff_role)');
            $this->db->bind(':clinic_id', $data['clinic_id']);
            $this->db->bind(':user_id', $user_id);
            $this->db->bind(':staff_role', $data['staff_role']);

            return $this->db->execute();
        }
        return false;
    }

    // Get all staff for a clinic
    public function getClinicStaff($clinic_id) {
        $this->db->query('SELECT u.id as user_id, u.first_name, u.last_name, u.email, cs.staff_role, cs.created_at
                          FROM clinic_staff cs
                          JOIN users u ON cs.user_id = u.id
                          WHERE cs.clinic_id = :clinic_id
                          ORDER BY u.first_name ASC');
        $this->db->bind(':clinic_id', $clinic_id);
        return $this->db->resultSet();
    }

    // Remove staff member
    public function removeStaff($user_id, $clinic_id) {
        // Double check clinic owns this staff member
        $this->db->query('SELECT id FROM clinic_staff WHERE user_id = :uid AND clinic_id = :cid');
        $this->db->bind(':uid', $user_id);
        $this->db->bind(':cid', $clinic_id);
        if($this->db->rowCount() > 0) {
            // Because of ON DELETE CASCADE, deleting the user deletes the clinic_staff link
            $this->db->query('DELETE FROM users WHERE id = :uid');
            $this->db->bind(':uid', $user_id);
            return $this->db->execute();
        }
        return false;
    }

    // Get clinic info and staff role for a logged in staff member
    public function getStaffDetails($user_id) {
        $this->db->query('SELECT cs.clinic_id, cs.staff_role, c.*
                          FROM clinic_staff cs
                          JOIN clinics c ON cs.clinic_id = c.id
                          WHERE cs.user_id = :uid');
        $this->db->bind(':uid', $user_id);
        return $this->db->single();
    }
}
