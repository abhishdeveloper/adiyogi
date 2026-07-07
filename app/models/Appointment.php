<?php
class Appointment {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Create a new appointment
    public function createAppointment($data) {
        $this->db->query('INSERT INTO appointments (clinic_id, patient_user_id, appointment_datetime, status, payment_method, amount) VALUES (:clinic_id, :patient_user_id, :appointment_datetime, :status, :payment_method, :amount)');

        $this->db->bind(':clinic_id', $data['clinic_id']);
        $this->db->bind(':patient_user_id', $data['patient_user_id']);
        $this->db->bind(':appointment_datetime', $data['appointment_datetime']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':payment_method', $data['payment_method']);
        $this->db->bind(':amount', $data['amount']);

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // Update razorpay order ID
    public function updateRazorpayOrder($appointment_id, $order_id) {
        $this->db->query('UPDATE appointments SET razorpay_order_id = :order_id WHERE id = :id');
        $this->db->bind(':order_id', $order_id);
        $this->db->bind(':id', $appointment_id);
        return $this->db->execute();
    }

    // Confirm payment
    public function confirmPayment($appointment_id, $payment_id) {
        $this->db->query('UPDATE appointments SET payment_status = "paid", status = "confirmed", razorpay_payment_id = :payment_id WHERE id = :id');
        $this->db->bind(':payment_id', $payment_id);
        $this->db->bind(':id', $appointment_id);
        return $this->db->execute();
    }

    // Get clinic's appointments
    public function getClinicAppointments($clinic_id) {
        $this->db->query('SELECT a.*, u.first_name, u.last_name, u.email
                          FROM appointments a
                          JOIN users u ON a.patient_user_id = u.id
                          WHERE a.clinic_id = :clinic_id
                          ORDER BY a.appointment_datetime DESC');
        $this->db->bind(':clinic_id', $clinic_id);
        return $this->db->resultSet();
    }

    // Get patient's appointments
    public function getPatientAppointments($patient_id) {
        $this->db->query('SELECT a.*, c.clinic_name, c.phone, c.address
                          FROM appointments a
                          JOIN clinics c ON a.clinic_id = c.id
                          WHERE a.patient_user_id = :patient_id
                          ORDER BY a.appointment_datetime DESC');
        $this->db->bind(':patient_id', $patient_id);
        return $this->db->resultSet();
    }

    // Calculate total earnings for a clinic
    public function getClinicEarnings($clinic_id) {
        $this->db->query('SELECT SUM(amount) as total FROM appointments WHERE clinic_id = :clinic_id AND payment_status = "paid"');
        $this->db->bind(':clinic_id', $clinic_id);
        $result = $this->db->single();
        return $result->total ? $result->total : 0.00;
    }

    // --- Analytics Methods --- //

    // Get appointments count for the last 7 days
    public function getWeeklyAppointmentsStats($clinic_id) {
        $this->db->query("
            SELECT DATE(appointment_datetime) as app_date, COUNT(*) as count
            FROM appointments
            WHERE clinic_id = :clinic_id AND appointment_datetime >= DATE(NOW()) - INTERVAL 7 DAY
            GROUP BY DATE(appointment_datetime)
            ORDER BY app_date ASC
        ");
        $this->db->bind(':clinic_id', $clinic_id);
        return $this->db->resultSet();
    }

    // Get monthly earnings for the last 6 months
    public function getMonthlyEarningsStats($clinic_id) {
        $this->db->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') as month_year, SUM(amount) as total
            FROM appointments
            WHERE clinic_id = :clinic_id AND payment_status = 'paid' AND created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY month_year
            ORDER BY month_year ASC
        ");
        $this->db->bind(':clinic_id', $clinic_id);
        return $this->db->resultSet();
    }

    // Get patient demographics (Gender breakdown)
    public function getPatientDemographics($clinic_id) {
        $this->db->query("
            SELECT pp.gender, COUNT(DISTINCT a.patient_user_id) as count
            FROM appointments a
            JOIN patient_profiles pp ON a.patient_user_id = pp.user_id
            WHERE a.clinic_id = :clinic_id AND pp.gender IS NOT NULL
            GROUP BY pp.gender
        ");
        $this->db->bind(':clinic_id', $clinic_id);
        return $this->db->resultSet();
    }
