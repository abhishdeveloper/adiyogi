<?php
/**
 * Automated Cron Script for sending 24-hour Appointment Reminders.
 * This file should be triggered via crontab.
 * Example: * * * * * php /path/to/app/cron/reminders.php
 */

// Define application root explicitly for CLI
define('APP_ROOT', dirname(dirname(__FILE__)));
require_once APP_ROOT . '/config/config.php';
require_once APP_ROOT . '/core/Database.php';
require_once APP_ROOT . '/core/WhatsappSmsService.php';

$db = new Database();

// Get the API Key from settings
$db->query("SELECT setting_value FROM settings WHERE setting_key = 'sms_api_key'");
$api_key_row = $db->single();
$api_key = $api_key_row ? $api_key_row->setting_value : '';

$smsService = new WhatsappSmsService($api_key);

// Find appointments exactly 24 hours from now (within a 1-minute window)
// E.g., if now is 10:00:00, find appointments between 10:00:00 to 10:00:59 tomorrow
$db->query("
    SELECT a.id as appointment_id, a.appointment_datetime,
           u.first_name, u.phone,
           c.clinic_name
    FROM appointments a
    JOIN users u ON a.patient_user_id = u.id
    JOIN clinics c ON a.clinic_id = c.id
    WHERE a.status = 'confirmed'
    AND a.appointment_datetime >= DATE_ADD(NOW(), INTERVAL 24 HOUR)
    AND a.appointment_datetime < DATE_ADD(NOW(), INTERVAL 24 HOUR + 1 MINUTE)
    AND u.phone IS NOT NULL AND u.phone != ''
");

$upcoming_appointments = $db->resultSet();

$sent_count = 0;

foreach ($upcoming_appointments as $app) {
    // Format datetime
    $time_formatted = date('g:i A on F jS', strtotime($app->appointment_datetime));

    // Construct reminder message
    $message = "Hi {$app->first_name},\n\nThis is a gentle reminder from {$app->clinic_name} that you have an upcoming appointment scheduled for tomorrow at {$time_formatted}.\n\nPlease arrive 10 minutes early. Reply to this message if you need to reschedule.";

    // Send SMS/WhatsApp
    $success = $smsService->sendReminder($app->phone, $message);

    if ($success) {
        echo "Reminder sent to +91{$app->phone} for Appt ID: {$app->appointment_id}\n";
        $sent_count++;
    } else {
        echo "Failed to send to +91{$app->phone} for Appt ID: {$app->appointment_id}\n";
    }
}

echo "Cron finished. Total reminders sent: {$sent_count}\n";
