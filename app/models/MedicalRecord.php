<?php
class MedicalRecord {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Add a new lab report
    public function addReport($data) {
        $this->db->query('INSERT INTO patient_lab_reports (patient_user_id, title, file_path, report_date, notes) VALUES (:uid, :title, :file_path, :report_date, :notes)');
        $this->db->bind(':uid', $data['patient_user_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':file_path', $data['file_path']);
        $this->db->bind(':report_date', $data['report_date']);
        $this->db->bind(':notes', $data['notes']);
        return $this->db->execute();
    }

    // Get all reports for a patient
    public function getPatientReports($patient_id) {
        $this->db->query('SELECT * FROM patient_lab_reports WHERE patient_user_id = :uid ORDER BY report_date DESC, created_at DESC');
        $this->db->bind(':uid', $patient_id);
        return $this->db->resultSet();
    }

    // Delete a report
    public function deleteReport($id, $patient_id) {
        $this->db->query('DELETE FROM patient_lab_reports WHERE id = :id AND patient_user_id = :uid');
        $this->db->bind(':id', $id);
        $this->db->bind(':uid', $patient_id);
        return $this->db->execute();
    }
}
