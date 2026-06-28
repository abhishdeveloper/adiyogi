<?php require APP_ROOT . '/app/views/inc/head.php'; ?>

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-3xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                    Book New Appointment
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manually schedule a patient.</p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="/clinicdashboard/index" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
            </div>
        </div>

        <?php if(!empty($data['error_msg'])): ?>
            <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-md shadow-sm">
                <p class="text-sm text-red-700"><?php echo $data['error_msg']; ?></p>
            </div>
        <?php endif; ?>

        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <form action="/clinicdashboard/book" method="POST" class="space-y-6">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($this->generateCsrfToken()); ?>">

                    <div class="bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-400 p-4 rounded-md mb-6">
                        <p class="text-sm text-blue-700 dark:text-blue-300">
                            <strong>Note:</strong> If the patient email does not exist, a new profile will be created automatically for them.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Patient First Name *</label>
                            <input type="text" name="first_name" required value="<?php echo htmlspecialchars($data['first_name']); ?>" class="mt-1 block w-full shadow-sm focus:ring-primary focus:border-primary sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Patient Last Name</label>
                            <input type="text" name="last_name" value="<?php echo htmlspecialchars($data['last_name']); ?>" class="mt-1 block w-full shadow-sm focus:ring-primary focus:border-primary sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Patient Email *</label>
                            <input type="email" name="email" required value="<?php echo htmlspecialchars($data['email']); ?>" class="mt-1 block w-full shadow-sm focus:ring-primary focus:border-primary sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date & Time *</label>
                            <input type="datetime-local" name="appointment_datetime" required value="<?php echo htmlspecialchars($data['appointment_datetime']); ?>" class="mt-1 block w-full shadow-sm focus:ring-primary focus:border-primary sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Consultation Fee (₹)</label>
                            <input type="number" step="0.01" name="amount" value="<?php echo htmlspecialchars($data['amount']); ?>" class="mt-1 block w-full shadow-sm focus:ring-primary focus:border-primary sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                        </div>

                    </div>

                    <div class="pt-5 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex justify-end">
                            <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-secondary focus:outline-none transition-colors">
                                Confirm Booking
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<?php require APP_ROOT . '/app/views/inc/tail.php'; ?>
