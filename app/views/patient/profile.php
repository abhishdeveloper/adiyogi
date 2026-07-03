<?php require APP_ROOT . '/app/views/inc/head.php'; ?>

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300 pb-safe">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                    Medical Profile
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="/patientdashboard/index" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    Back to Dashboard
                </a>
            </div>
        </div>

        <?php if(!empty($data['success_msg'])): ?>
            <div class="rounded-md bg-green-50 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0"><i class="fa-solid fa-circle-check text-green-400"></i></div>
                    <div class="ml-3"><p class="text-sm font-medium text-green-800"><?php echo htmlspecialchars($data['success_msg']); ?></p></div>
                </div>
            </div>
        <?php endif; ?>

        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <form action="/patientdashboard/profile" method="POST" class="space-y-6">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($this->generateCsrfToken()); ?>">

                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date of Birth</label>
                            <input type="date" name="dob" value="<?php echo htmlspecialchars($data['profile']->dob ?? ''); ?>" class="mt-1 shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gender</label>
                            <select name="gender" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md dark:bg-gray-700 dark:text-white">
                                <option value="">Select...</option>
                                <option value="male" <?php echo ($data['profile']->gender ?? '') == 'male' ? 'selected' : ''; ?>>Male</option>
                                <option value="female" <?php echo ($data['profile']->gender ?? '') == 'female' ? 'selected' : ''; ?>>Female</option>
                                <option value="other" <?php echo ($data['profile']->gender ?? '') == 'other' ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Blood Group</label>
                            <input type="text" name="blood_group" value="<?php echo htmlspecialchars($data['profile']->blood_group ?? ''); ?>" placeholder="e.g. O+, A-" class="mt-1 shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                        </div>

                        <div class="sm:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Emergency Contact</label>
                            <input type="text" name="emergency_contact" value="<?php echo htmlspecialchars($data['profile']->emergency_contact ?? ''); ?>" class="mt-1 shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                        </div>

                        <div class="sm:col-span-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Allergies</label>
                            <textarea name="allergies" rows="2" class="mt-1 shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white" placeholder="List any known allergies..."><?php echo htmlspecialchars($data['profile']->allergies ?? ''); ?></textarea>
                        </div>

                        <div class="sm:col-span-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Medical History</label>
                            <textarea name="medical_history" rows="4" class="mt-1 shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white" placeholder="Past surgeries, chronic conditions, regular medications..."><?php echo htmlspecialchars($data['profile']->medical_history ?? ''); ?></textarea>
                        </div>
                    </div>

                    <div class="pt-5 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex justify-end">
                            <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                Save Profile
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APP_ROOT . '/app/views/inc/tail.php'; ?>
