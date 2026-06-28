<?php require APP_ROOT . '/app/views/inc/head.php'; ?>

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white dark:bg-gray-800 py-8 px-4 shadow sm:rounded-lg sm:px-10 border border-gray-100 dark:border-gray-700 text-center">

            <i class="fa-solid fa-spinner fa-spin text-4xl text-primary mb-4"></i>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Redirecting to Payment...</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Please do not refresh the page.</p>

            <!-- Razorpay Integration Form -->
            <form action="/patientdashboard/verify_payment" method="POST" name="razorpayform">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($this->generateCsrfToken()); ?>">
                <input type="hidden" name="appointment_id" value="<?php echo htmlspecialchars($data['appointment_id']); ?>">
                <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
                <input type="hidden" name="razorpay_signature" id="razorpay_signature">
                <input type="hidden" name="razorpay_order_id" id="razorpay_order_id" value="<?php echo htmlspecialchars($data['razorpay_order_id']); ?>">
            </form>

            <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
            <script>
                var options = {
                    "key": "<?php echo $data['key_id']; ?>",
                    "amount": "<?php echo $data['amount'] * 100; ?>",
                    "currency": "INR",
                    "name": "<?php echo htmlspecialchars($data['clinic']->clinic_name); ?>",
                    "description": "Consultation Fee",
                    "image": "<?php echo $data['clinic']->profile_image ? '/assets/uploads/profiles/' . htmlspecialchars($data['clinic']->profile_image) : ''; ?>",
                    "order_id": "<?php echo htmlspecialchars($data['razorpay_order_id']); ?>",
                    "handler": function (response){
                        document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                        document.getElementById('razorpay_signature').value = response.razorpay_signature;
                        document.razorpayform.submit();
                    },
                    "prefill": {
                        "name": "<?php echo htmlspecialchars($data['user_name']); ?>",
                        "email": "<?php echo htmlspecialchars($data['user_email']); ?>"
                    },
                    "theme": {
                        "color": "#0ea5e9"
                    },
                    "modal": {
                        "ondismiss": function(){
                            window.location.href = '/patientdashboard/index?error=paymentcancelled';
                        }
                    }
                };
                var rzp1 = new Razorpay(options);

                // Automatically open the checkout modal
                window.onload = function() {
                    rzp1.open();
                };
            </script>
        </div>
    </div>
</div>

<?php require APP_ROOT . '/app/views/inc/tail.php'; ?>
