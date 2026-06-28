<?php require APP_ROOT . '/app/views/inc/head.php'; ?>

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-2xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                    Book Appointment
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Schedule your visit with <?php echo htmlspecialchars($data['clinic']->clinic_name); ?></p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="/patientdashboard/index" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    Back
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <form action="/patientdashboard/book/<?php echo $data['clinic']->id; ?>" method="POST" class="space-y-6">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($this->generateCsrfToken()); ?>">

                    <div class="grid grid-cols-1 gap-y-6">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select Date & Time</label>
                            <input type="datetime-local" name="appointment_datetime" required class="mt-1 block w-full shadow-sm focus:ring-primary focus:border-primary sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                            <p class="text-xs text-gray-500 mt-1">Please select your preferred time. The clinic will confirm availability.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Method</label>

                            <div class="space-y-4 sm:flex sm:items-center sm:space-y-0 sm:space-x-10">
                                <?php if($data['payment_pref'] == 'both' || $data['payment_pref'] == 'online'): ?>
                                <div class="flex items-center">
                                    <input id="pay_online" name="payment_method" type="radio" value="online" checked class="focus:ring-primary h-4 w-4 text-primary border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                    <label for="pay_online" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Pay Online Now (Razorpay)
                                    </label>
                                </div>
                                <?php endif; ?>

                                <?php if($data['payment_pref'] == 'both' || $data['payment_pref'] == 'offline'): ?>
                                <div class="flex items-center">
                                    <input id="pay_offline" name="payment_method" type="radio" value="offline" <?php echo ($data['payment_pref'] == 'offline') ? 'checked' : ''; ?> class="focus:ring-primary h-4 w-4 text-primary border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                    <label for="pay_offline" class="ml-3 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Pay at Clinic
                                    </label>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Consultation Fee</span>
                            <span class="text-lg font-bold text-gray-900 dark:text-white">₹500.00</span>
                        </div>
                    </div>

                    <div class="pt-5 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-secondary focus:outline-none transition-colors">
                            Confirm Appointment
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<?php require APP_ROOT . '/app/views/inc/tail.php'; ?>
