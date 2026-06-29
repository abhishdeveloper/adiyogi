<?php
class PresavedItem {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function addItem($data) {
        $this->db->query("INSERT INTO presaved_items (clinic_id, item_type, title, content) VALUES (:cid, :type, :title, :content)");
        $this->db->bind(':cid', $data['clinic_id']);
        $this->db->bind(':type', $data['item_type']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':content', $data['content']);
        return $this->db->execute();
    }

    public function getItemsByClinic($clinic_id, $type = null) {
        if($type) {
            $this->db->query("SELECT * FROM presaved_items WHERE clinic_id = :cid AND item_type = :type ORDER BY title ASC");
            $this->db->bind(':type', $type);
        } else {
            $this->db->query("SELECT * FROM presaved_items WHERE clinic_id = :cid ORDER BY item_type ASC, title ASC");
        }
        $this->db->bind(':cid', $clinic_id);
        return $this->db->resultSet();
    }

    public function deleteItem($id, $clinic_id) {
        $this->db->query("DELETE FROM presaved_items WHERE id = :id AND clinic_id = :cid");
        $this->db->bind(':id', $id);
        $this->db->bind(':cid', $clinic_id);
        return $this->db->execute();
    }
}
