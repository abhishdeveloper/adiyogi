<?php require APP_ROOT . '/app/views/inc/head.php'; ?>

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8 transition-colors duration-300">
    <div class="max-w-md mx-auto animate-on-scroll">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">Patient Registration</h2>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Create your account to connect with your clinic.</p>
        </div>

        <div class="bg-white dark:bg-gray-800 py-8 px-4 shadow sm:rounded-lg sm:px-10 border border-gray-100 dark:border-gray-700">
            <form class="space-y-6" action="/auth/patient_register" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($this->generateCsrfToken()); ?>">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">First Name</label>
                        <input type="text" name="first_name" id="first_name" value="<?php echo htmlspecialchars($data['first_name']); ?>" required class="mt-1 appearance-none block w-full px-3 py-2 border <?php echo (!empty($data['first_name_err'])) ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'; ?> rounded-md shadow-sm sm:text-sm dark:bg-gray-700 dark:text-white">
                        <span class="text-red-500 text-xs mt-1 block"><?php echo $data['first_name_err']; ?></span>
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Last Name</label>
                        <input type="text" name="last_name" id="last_name" value="<?php echo htmlspecialchars($data['last_name']); ?>" required class="mt-1 appearance-none block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm sm:text-sm dark:bg-gray-700 dark:text-white">
                    </div>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone Number (WhatsApp/SMS)</label>
                    <input id="phone" name="phone" type="text" value="<?php echo htmlspecialchars($data['phone'] ?? ''); ?>" required class="mt-1 appearance-none block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm sm:text-sm dark:bg-gray-700 dark:text-white">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email address</label>
                    <input id="email" name="email" type="email" value="<?php echo htmlspecialchars($data['email']); ?>" required class="mt-1 appearance-none block w-full px-3 py-2 border <?php echo (!empty($data['email_err'])) ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'; ?> rounded-md shadow-sm sm:text-sm dark:bg-gray-700 dark:text-white">
                    <span class="text-red-500 text-xs mt-1 block"><?php echo $data['email_err']; ?></span>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                    <input id="password" name="password" type="password" required class="mt-1 appearance-none block w-full px-3 py-2 border <?php echo (!empty($data['password_err'])) ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'; ?> rounded-md shadow-sm sm:text-sm dark:bg-gray-700 dark:text-white">
                    <span class="text-red-500 text-xs mt-1 block"><?php echo $data['password_err']; ?></span>
                </div>

                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm Password</label>
                    <input id="confirm_password" name="confirm_password" type="password" required class="mt-1 appearance-none block w-full px-3 py-2 border <?php echo (!empty($data['confirm_password_err'])) ? 'border-red-500' : 'border-gray-300 dark:border-gray-600'; ?> rounded-md shadow-sm sm:text-sm dark:bg-gray-700 dark:text-white">
                    <span class="text-red-500 text-xs mt-1 block"><?php echo $data['confirm_password_err']; ?></span>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                        Register
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
                    <a href="/auth/google?role=patient" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-sm font-medium text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                        <i class="fa-brands fa-google text-red-500 mr-2 text-lg"></i>
                        <span>Register with Google</span>
                    </a>
                </div>
            </div>

            <div class="mt-4 text-center">
                 <a href="/auth/login" class="text-sm font-medium text-primary hover:text-secondary transition-colors">
                    Already have an account? Sign in.
                </a>
            </div>
        </div>
    </div>
</div>

<?php require APP_ROOT . '/app/views/inc/tail.php'; ?>
