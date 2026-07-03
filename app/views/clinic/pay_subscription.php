<?php require APP_ROOT . '/app/views/inc/head.php'; ?>

<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8 pb-safe">
    <div class="max-w-md w-full space-y-8 bg-white dark:bg-gray-800 p-8 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 text-center">

        <div>
            <i class="fa-solid fa-crown text-yellow-500 text-6xl mb-4"></i>
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">
                Upgrade to Premium
            </h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Unlock custom brand colors, patient chat, and more!
            </p>
        </div>

        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 my-6 text-left">
            <div class="flex justify-between border-b border-gray-200 dark:border-gray-600 pb-3 mb-3">
                <span class="text-gray-600 dark:text-gray-300 font-medium">Plan:</span>
                <span class="text-gray-900 dark:text-white font-bold">Premium Monthly</span>
            </div>
            <div class="flex justify-between border-b border-gray-200 dark:border-gray-600 pb-3 mb-3">
                <span class="text-gray-600 dark:text-gray-300 font-medium">Billed To:</span>
                <span class="text-gray-900 dark:text-white font-bold"><?php echo htmlspecialchars($data['clinic']->clinic_name); ?></span>
            </div>
            <div class="flex justify-between items-center pt-2">
                <span class="text-gray-600 dark:text-gray-300 font-medium">Total Amount:</span>
                <span class="text-3xl text-primary font-extrabold">₹<?php echo number_format($data['amount'], 2); ?></span>
            </div>
        </div>

        <button id="rzp-button" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-lg font-medium text-white bg-primary hover:bg-secondary focus:outline-none transition-colors">
            Pay Securely Now
        </button>

        <form action="/clinicdashboard/verify_subscription" method="POST" id="verify-form" class="hidden">
            <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
            <input type="hidden" name="razorpay_order_id" id="razorpay_order_id">
            <input type="hidden" name="razorpay_signature" id="razorpay_signature">
        </form>

    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
var options = {
    "key": "<?php echo $data['key_id']; ?>",
    "amount": "<?php echo $data['amount'] * 100; ?>",
    "currency": "INR",
    "name": "MedClinicPro",
    "description": "Premium Monthly Subscription",
    "image": "https://dummyimage.com/150x150/0ea5e9/fff&text=MCP",
    "order_id": "<?php echo $data['razorpay_order_id']; ?>",
    "handler": function (response){
        document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
        document.getElementById('razorpay_order_id').value = response.razorpay_order_id;
        document.getElementById('razorpay_signature').value = response.razorpay_signature;
        document.getElementById('verify-form').submit();
    },
    "prefill": {
        "name": "<?php echo $data['user_name']; ?>",
        "email": "<?php echo $data['user_email']; ?>",
        "contact": "<?php echo $data['clinic']->phone; ?>"
    },
    "theme": {
        "color": "#0ea5e9"
    }
};
var rzp1 = new Razorpay(options);
rzp1.on('payment.failed', function (response){
    alert("Payment Failed: " + response.error.description);
});
document.getElementById('rzp-button').onclick = function(e){
    rzp1.open();
    e.preventDefault();
}
</script>

<?php require APP_ROOT . '/app/views/inc/tail.php'; ?>
