<?php require APP_ROOT . '/app/views/inc/head.php'; ?>

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                    My Dashboard
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4 gap-3">
                <a href="/patientdashboard/records" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors hidden md:inline-flex">
                    <i class="fa-solid fa-file-medical mr-2"></i> Records
                </a>
                <a href="/patientdashboard/billing" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors hidden md:inline-flex">
                    <i class="fa-solid fa-receipt mr-2"></i> Billing
                </a>
                <a href="/patientdashboard/profile" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-secondary transition-colors hidden md:inline-flex">
                    <i class="fa-solid fa-notes-medical mr-2"></i> Medical Profile
                </a>
                <a href="/auth/logout" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    Logout
                </a>
            </div>
        </div>

        <?php if(!empty($data['success_msg'])): ?>
            <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-md shadow-sm">
                <p class="text-sm text-green-700"><?php echo $data['success_msg']; ?></p>
            </div>
        <?php endif; ?>
        <?php if(!empty($data['error_msg'])): ?>
            <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-md shadow-sm">
                <p class="text-sm text-red-700"><?php echo $data['error_msg']; ?></p>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Link New Clinic -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg border border-gray-200 dark:border-gray-700 mb-8">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-2">Connect to a Clinic</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Enter the unique code provided by your doctor to link their profile to your account.</p>

                        <form action="/patientdashboard/link_clinic" method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($this->generateCsrfToken()); ?>">
                            <div class="flex">
                                <input type="text" name="clinic_code" required placeholder="e.g. CLINIC-1A2B3C" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-l-md focus:ring-primary focus:border-primary sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white border-r-0">
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-r-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-secondary focus:outline-none transition-colors">
                                    Link
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- My Clinics -->
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">My Clinics</h3>
                    </div>
                    <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                        <?php if(empty($data['clinics'])): ?>
                            <li class="px-4 py-4 text-sm text-gray-500 dark:text-gray-400 text-center">You haven't linked any clinics yet.</li>
                        <?php else: ?>
                            <?php foreach($data['clinics'] as $clinic): ?>
                                <li class="px-4 py-4 sm:px-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-primary truncate"><?php echo htmlspecialchars($clinic->clinic_name); ?></p>
                                            <p class="flex items-center text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                <i class="fa-solid fa-stethoscope mr-1.5 w-4 text-center"></i>
                                                <?php echo htmlspecialchars($clinic->specialty); ?>
                                            </p>
                                        </div>
                                        <div class="flex flex-col gap-2">
                                            <a href="/patientdashboard/book/<?php echo $clinic->id; ?>" class="inline-flex items-center justify-center px-3 py-1 border border-transparent text-xs font-medium rounded text-white bg-green-600 hover:bg-green-700 transition-colors">
                                                Book
                                            </a>
                                            <a href="/profile/show/<?php echo htmlspecialchars($clinic->url_slug); ?>" target="_blank" class="inline-flex items-center justify-center px-3 py-1 border border-gray-300 dark:border-gray-600 text-xs font-medium rounded text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 transition-colors">
                                                Profile
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <!-- Appointments List -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg border border-gray-200 dark:border-gray-700 h-full">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Appointment History</h3>
                    </div>
                    <?php if(empty($data['appointments'])): ?>
                        <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                            <i class="fa-regular fa-calendar-xmark text-4xl mb-3"></i>
                            <p>No appointments found.</p>
                        </div>
                    <?php else: ?>
                        <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                            <?php foreach($data['appointments'] as $app): ?>
                                <li class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <div class="text-sm font-medium text-primary truncate">
                                            <?php echo htmlspecialchars($app->clinic_name); ?>
                                        </div>
                                        <div class="ml-2 flex-shrink-0 flex">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                <?php echo $app->status == 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                                <?php echo ucfirst($app->status); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-2 sm:flex sm:justify-between">
                                        <div class="sm:flex">
                                            <p class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                                <i class="fa-regular fa-calendar mr-1.5"></i>
                                                <?php echo date('F j, Y, g:i a', strtotime($app->appointment_datetime)); ?>
                                            </p>
                                        </div>
                                        <div class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400 sm:mt-0 gap-4">
                                            <span><i class="fa-solid fa-wallet mr-1.5"></i> ₹<?php echo number_format($app->amount, 2); ?></span>
                                            <span>
                                                <?php if($app->payment_status == 'paid'): ?>
                                                    <span class="text-green-600 dark:text-green-400"><i class="fa-solid fa-check-circle mr-1"></i> Paid (<?php echo ucfirst($app->payment_method); ?>)</span>
                                                <?php else: ?>
                                                    <span class="text-yellow-600 dark:text-yellow-400"><i class="fa-solid fa-clock mr-1"></i> Pending (<?php echo ucfirst($app->payment_method); ?>)</span>
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mt-2 text-sm text-gray-500 dark:text-gray-400 flex items-start">
                                         <i class="fa-solid fa-location-dot mt-1 mr-1.5 text-gray-400 w-3"></i>
                                         <span><?php echo htmlspecialchars($app->address); ?></span>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require APP_ROOT . '/app/views/inc/tail.php'; ?>
