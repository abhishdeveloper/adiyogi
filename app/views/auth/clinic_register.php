<?php require APP_ROOT . '/app/views/inc/head.php'; ?>

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8 transition-colors duration-300">
    <div class="max-w-2xl mx-auto animate-on-scroll">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">Clinic Registration</h2>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Join our secure platform to manage your medical practice.</p>
            <p class="text-sm text-yellow-600 dark:text-yellow-500 mt-2"><i class="fa-solid fa-triangle-exclamation mr-1"></i> Note: All clinic accounts require administrator verification before activation.</p>
        </div>

        <div class="bg-white dark:bg-gray-800 py-8 px-4 shadow sm:rounded-lg sm:px-10 border border-gray-100 dark:border-gray-700">
            <form class="space-y-6" action="/auth/clinic_register" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($this->generateCsrfToken()); ?>">

                <h3 class="text-lg font-medium text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2">Admin User Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">First Name</label>
                        <input type="text" name="first_name" value="<?php echo htmlspecialchars($data['first_name'] ?? ''); ?>" required class="mt-1 appearance-none block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm sm:text-sm dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Last Name</label>
                        <input type="text" name="last_name" value="<?php echo htmlspecialchars($data['last_name'] ?? ''); ?>" required class="mt-1 appearance-none block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm sm:text-sm dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>" <?php echo isset($data['google_id']) ? 'readonly' : 'required'; ?> class="mt-1 appearance-none block w-full px-3 py-2 border <?php echo (!empty($data['email_err'])) ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'; ?> rounded-md shadow-sm sm:text-sm dark:bg-gray-700 dark:text-white">
                        <span class="text-red-500 text-xs mt-1 block"><?php echo $data['email_err'] ?? ''; ?></span>
                    </div>
                    <?php if (isset($data['google_id'])): ?>
                        <input type="hidden" name="google_id" value="<?php echo htmlspecialchars($data['google_id']); ?>">
                        <input type="hidden" name="password" value="<?php echo bin2hex(random_bytes(16)); ?>">
                        <input type="hidden" name="confirm_password" value="">
                    <?php else: ?>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                            <input type="password" name="password" required class="mt-1 appearance-none block w-full px-3 py-2 border <?php echo (!empty($data['password_err'])) ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'; ?> rounded-md shadow-sm sm:text-sm dark:bg-gray-700 dark:text-white">
                            <span class="text-red-500 text-xs mt-1 block"><?php echo $data['password_err'] ?? ''; ?></span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm Password</label>
                            <input type="password" name="confirm_password" required class="mt-1 appearance-none block w-full px-3 py-2 border <?php echo (!empty($data['confirm_password_err'])) ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'; ?> rounded-md shadow-sm sm:text-sm dark:bg-gray-700 dark:text-white">
                            <span class="text-red-500 text-xs mt-1 block"><?php echo $data['confirm_password_err'] ?? ''; ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <h3 class="text-lg font-medium text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-2 mt-8">Clinic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Clinic / Practice Name</label>
                        <input type="text" name="clinic_name" value="<?php echo htmlspecialchars($data['clinic_name'] ?? ''); ?>" required class="mt-1 appearance-none block w-full px-3 py-2 border <?php echo (!empty($data['clinic_name_err'])) ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'; ?> rounded-md shadow-sm sm:text-sm dark:bg-gray-700 dark:text-white">
                        <span class="text-red-500 text-xs mt-1 block"><?php echo $data['clinic_name_err'] ?? ''; ?></span>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Custom Linkage Code (Optional)</label>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Patients will use this code to link to your clinic. Leave blank to auto-generate.</p>
                        <input type="text" name="custom_code" value="<?php echo htmlspecialchars($data['custom_code'] ?? ''); ?>" placeholder="e.g. MYCLINIC-123" class="mt-1 appearance-none block w-full px-3 py-2 border <?php echo (!empty($data['code_err'])) ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'; ?> rounded-md shadow-sm sm:text-sm dark:bg-gray-700 dark:text-white">
                        <span class="text-red-500 text-xs mt-1 block"><?php echo $data['code_err'] ?? ''; ?></span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Medical License Number</label>
                        <input type="text" name="license_number" value="<?php echo htmlspecialchars($data['license_number'] ?? ''); ?>" required class="mt-1 appearance-none block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm sm:text-sm dark:bg-gray-700 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Primary Specialty</label>
                        <input type="text" name="specialty" value="<?php echo htmlspecialchars($data['specialty'] ?? ''); ?>" required class="mt-1 appearance-none block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm sm:text-sm dark:bg-gray-700 dark:text-white">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone Number</label>
                        <input type="text" name="phone" value="<?php echo htmlspecialchars($data['phone'] ?? ''); ?>" class="mt-1 appearance-none block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm sm:text-sm dark:bg-gray-700 dark:text-white">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                        <input type="text" name="address" value="<?php echo htmlspecialchars($data['address'] ?? ''); ?>" required class="mt-1 appearance-none block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm sm:text-sm dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">City</label>
                        <input type="text" name="city" value="<?php echo htmlspecialchars($data['city'] ?? ''); ?>" required class="mt-1 appearance-none block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm sm:text-sm dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">State</label>
                            <input type="text" name="state" value="<?php echo htmlspecialchars($data['state'] ?? ''); ?>" required class="mt-1 appearance-none block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm sm:text-sm dark:bg-gray-700 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">ZIP</label>
                            <input type="text" name="zip" value="<?php echo htmlspecialchars($data['zip'] ?? ''); ?>" required class="mt-1 appearance-none block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm sm:text-sm dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                        Submit Clinic for Verification
                    </button>
                </div>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400">Or register with</span>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-3">
                    <a href="/auth/google?role=clinic" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-sm font-medium text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        <i class="fa-brands fa-google text-red-500 mr-2 text-lg"></i>
                        <span>Register with Google</span>
                    </a>
                </div>
            </div>

            <div class="mt-6 text-center">
                 <a href="/auth/login" class="text-sm font-medium text-primary hover:text-secondary transition-colors">
                    Already have an account? Sign in.
                </a>
            </div>
        </div>
    </div>
</div>

<?php require APP_ROOT . '/app/views/inc/tail.php'; ?>
