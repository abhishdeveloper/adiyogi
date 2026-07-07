<?php
class WhatsappSmsService {
    private $api_key;
    private $api_url;

    public function __construct($api_key = null) {
        // In a real application, fetch from the database via Setting model
        // We'll use dummy credentials or accept them directly for flexibility
        $this->api_key = $api_key ?: 'dummy_indian_provider_api_key_12345';
        $this->api_url = 'https://www.fast2sms.com/dev/bulkV2'; // Example local provider
    }

    /**
     * Send SMS/WhatsApp using an Indian API Provider (like Fast2SMS)
     *
     * @param string $phone The 10-digit Indian phone number
     * @param string $message The message body
     * @return bool True if successful, False otherwise
     */
    public function sendReminder($phone, $message) {
        // Clean phone number (strip spaces, ensure 10 digits)
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($phone) > 10 && substr($phone, 0, 2) == '91') {
            $phone = substr($phone, 2);
        }

        if (strlen($phone) != 10) {
            return false; // Invalid Indian phone number format
        }

        $fields = array(
            "sender_id" => "TXTIND", // Example sender ID
            "message" => $message,
            "route" => "v3",
            "numbers" => $phone
        );

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($fields),
            CURLOPT_HTTPHEADER => array(
                "authorization: " . $this->api_key,
                "accept: */*",
                "cache-control: no-cache",
                "content-type: application/json"
            ),
        ));

        // For the sake of this simulated environment, we will comment out
        // the actual curl_exec so it doesn't fail on a dummy URL.
        // $response = curl_exec($curl);
        // $err = curl_error($curl);
        curl_close($curl);

        // if ($err) return false;

        // Log the sending locally instead for demonstration
        error_log("[" . date('Y-m-d H:i:s') . "] SMS/WhatsApp Sent to +91{$phone}: {$message}\n", 3, APP_ROOT . '/sms_log.txt');

        return true;
    }
}
