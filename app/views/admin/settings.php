<?php require APP_ROOT . '/app/views/inc/head.php'; ?>

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                    System Settings
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="/admin/index" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    Back to Dashboard
                </a>
            </div>
        </div>

        <?php if(!empty($data['success_msg'])): ?>
            <div class="mb-6 bg-green-50 dark:bg-green-900/30 border-l-4 border-green-400 p-4 rounded-md shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-circle-check text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700 dark:text-green-400">
                            <?php echo $data['success_msg']; ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg border border-gray-100 dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                    Google OAuth 2.0 Credentials
                </h3>
                <div class="mt-2 max-w-xl text-sm text-gray-500 dark:text-gray-400">
                    <p>Enter the credentials from your Google Cloud Console to enable "Sign in with Google" for your users.</p>
                </div>

                <form action="/admin/settings" method="POST" class="mt-5 sm:flex sm:items-center flex-col space-y-4 items-start w-full max-w-2xl">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($this->generateCsrfToken()); ?>">
                    <div class="w-full">
                        <label for="google_client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Client ID</label>
                        <input type="text" name="google_client_id" id="google_client_id" value="<?php echo htmlspecialchars($data['google_client_id']); ?>" class="mt-1 block w-full shadow-sm focus:ring-primary focus:border-primary sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md p-2 border transition-colors">
                    </div>

                    <div class="w-full">
                        <label for="google_client_secret" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Client Secret</label>
                        <input type="password" name="google_client_secret" id="google_client_secret" value="<?php echo htmlspecialchars($data['google_client_secret']); ?>" class="mt-1 block w-full shadow-sm focus:ring-primary focus:border-primary sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md p-2 border transition-colors">
                    </div>

                    <div class="w-full pt-4">
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent font-medium rounded-md text-white bg-primary hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary shadow-sm sm:text-sm w-auto transition-colors">
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<?php require APP_ROOT . '/app/views/inc/tail.php'; ?>
