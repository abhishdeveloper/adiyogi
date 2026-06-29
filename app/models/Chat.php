<?php
class Chat {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function saveMessage($data) {
        $this->db->query("INSERT INTO chat_messages (appointment_id, sender_id, message, file_path) VALUES (:aid, :sid, :msg, :file)");
        $this->db->bind(':aid', $data['appointment_id']);
        $this->db->bind(':sid', $data['sender_id']);
        $this->db->bind(':msg', $data['message']);
        $this->db->bind(':file', $data['file_path']);
        return $this->db->execute();
    }

    public function getMessages($appointment_id, $last_id = 0) {
        $this->db->query("SELECT m.*, u.first_name, u.role FROM chat_messages m JOIN users u ON m.sender_id = u.id WHERE m.appointment_id = :aid AND m.id > :last_id ORDER BY m.created_at ASC");
        $this->db->bind(':aid', $appointment_id);
        $this->db->bind(':last_id', $last_id);
        return $this->db->resultSet();
    }
}
