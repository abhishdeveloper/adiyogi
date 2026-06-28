<?php require APP_ROOT . '/app/views/inc/head.php'; ?>

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8 transition-colors duration-300">
    <div class="max-w-md mx-auto animate-on-scroll">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white">Choose Account Type</h2>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Select how you want to use MedClinicPro</p>
        </div>

        <div class="grid grid-cols-1 gap-6">
            <a href="/auth/patient_register" class="flex items-center p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:border-primary dark:hover:border-primary transition-colors cursor-pointer group">
                <div class="h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                    <i class="fa-solid fa-hospital-user text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">I am a Patient</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Book appointments and manage your health records.</p>
                </div>
            </a>

            <a href="/auth/clinic_register" class="flex items-center p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:border-primary dark:hover:border-primary transition-colors cursor-pointer group">
                <div class="h-12 w-12 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center text-green-600 group-hover:bg-green-600 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-user-doctor text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">I am a Clinic / Doctor</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Manage your practice, staff, and patients securely.</p>
                </div>
            </a>
        </div>

        <div class="mt-6 text-center">
             <a href="/auth/login" class="font-medium text-primary hover:text-secondary transition-colors">
                Already have an account? Sign in.
            </a>
        </div>
    </div>
</div>

<?php require APP_ROOT . '/app/views/inc/tail.php'; ?>
