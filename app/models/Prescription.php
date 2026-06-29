<?php
class Prescription extends Controller {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function save($data) {
        $this->db->query("INSERT INTO prescriptions (appointment_id, clinic_id, patient_user_id, diagnosis, medicines, diet_instructions, notes) VALUES (:aid, :cid, :pid, :diag, :meds, :diet, :notes)");
        $this->db->bind(':aid', $data['appointment_id']);
        $this->db->bind(':cid', $data['clinic_id']);
        $this->db->bind(':pid', $data['patient_user_id']);
        $this->db->bind(':diag', $data['diagnosis']);
        $this->db->bind(':meds', $data['medicines']);
        $this->db->bind(':diet', $data['diet_instructions']);
        $this->db->bind(':notes', $data['notes']);

        if($this->db->execute()) {
            // Update appointment status
            $this->db->query("UPDATE appointments SET status = 'completed' WHERE id = :aid");
            $this->db->bind(':aid', $data['appointment_id']);
            $this->db->execute();
            return true;
        }
        return false;
    }

    public function getByAppointment($appointment_id) {
        $this->db->query("SELECT p.*, c.clinic_name, c.phone as clinic_phone, c.address as clinic_address, u.first_name as patient_first, u.last_name as patient_last, a.appointment_datetime FROM prescriptions p JOIN clinics c ON p.clinic_id = c.id JOIN users u ON p.patient_user_id = u.id JOIN appointments a ON p.appointment_id = a.id WHERE p.appointment_id = :aid");
        $this->db->bind(':aid', $appointment_id);
        return $this->db->single();
    }
}
