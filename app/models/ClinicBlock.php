<?php
class ClinicBlock {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Block a time slot
    public function blockTime($data) {
        $this->db->query('INSERT INTO clinic_blocked_time (clinic_id, start_datetime, end_datetime, reason) VALUES (:clinic_id, :start_datetime, :end_datetime, :reason)');
        $this->db->bind(':clinic_id', $data['clinic_id']);
        $this->db->bind(':start_datetime', $data['start_datetime']);
        $this->db->bind(':end_datetime', $data['end_datetime']);
        $this->db->bind(':reason', $data['reason']);
        return $this->db->execute();
    }

    // Get blocked times in a range
    public function getBlockedTimes($clinic_id, $start, $end) {
        $this->db->query("
            SELECT * FROM clinic_blocked_time
            WHERE clinic_id = :clinic_id
            AND start_datetime >= :start
            AND end_datetime <= :end
        ");
        $this->db->bind(':clinic_id', $clinic_id);
        $this->db->bind(':start', $start);
        $this->db->bind(':end', $end);
        return $this->db->resultSet();
    }

    // Delete a blocked time
    public function deleteBlock($id, $clinic_id) {
        $this->db->query('DELETE FROM clinic_blocked_time WHERE id = :id AND clinic_id = :clinic_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':clinic_id', $clinic_id);
        return $this->db->execute();
    }
}
