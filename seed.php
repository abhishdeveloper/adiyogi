<?php
require_once 'config/config.php';
require_once 'core/Database.php';

$db = new Database();

// 1. Create a verified clinic user
$db->query("INSERT INTO users (role, email, password, first_name, last_name, is_active) VALUES ('clinic', 'drsmith@example.com', 'dummyhash', 'John', 'Smith', 1)");
$db->execute();
$clinic_user_id = $db->lastInsertId();

// 2. Create the clinic details
$db->query("INSERT INTO clinics (user_id, clinic_name, unique_code, url_slug, phone, address, city, state, zip, specialty, is_verified, bio, theme_preference) VALUES (:uid, 'Smith Cardiology', 'SMITH123', 'smith-cardio', '555-1234', '123 Heart Way', 'Health City', 'HC', '12345', 'Cardiology', 1, 'We care about your heart.', 'minimal')");
$db->bind(':uid', $clinic_user_id);
$db->execute();
$clinic_id = $db->lastInsertId();

// 3. Create a patient user
$db->query("INSERT INTO users (role, email, password, first_name, last_name, is_active) VALUES ('patient', 'patient@example.com', :hash, 'Jane', 'Doe', 1)");
$db->bind(':hash', password_hash('password123', PASSWORD_BCRYPT));
$db->execute();
$patient_user_id = $db->lastInsertId();

// 4. Link patient to clinic
$db->query("INSERT INTO patient_clinic_links (patient_user_id, clinic_id) VALUES (:pid, :cid)");
$db->bind(':pid', $patient_user_id);
$db->bind(':cid', $clinic_id);
$db->execute();

echo "Seeded! Clinic Slug: smith-cardio";
