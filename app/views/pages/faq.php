<?php require APP_ROOT . '/app/views/inc/head.php'; ?>

<div class="bg-gray-50 dark:bg-gray-800 py-16 transition-colors duration-300 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 animate-on-scroll">
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white sm:text-4xl">
                Frequently Asked Questions
            </h1>
            <p class="mt-4 text-lg text-gray-500 dark:text-gray-400">
                Find answers to common questions about MedClinicPro's platform and security.
            </p>
        </div>

        <div class="space-y-6">
            <!-- FAQ Item -->
            <div class="bg-white dark:bg-gray-900 shadow-sm rounded-lg p-6 animate-on-scroll animate-delay-100">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Is my patients' data secure on shared hosting?</h3>
                <p class="mt-3 text-base text-gray-500 dark:text-gray-400">
                    Yes. We utilize application-level encryption, secure routing via our custom MVC framework, and strict directory restrictions (.htaccess). While dedicated servers offer hardware isolation, our software architecture ensures data separation via unique clinic IDs and prevents unauthorized access regardless of the hosting environment.
                </p>
            </div>

            <!-- FAQ Item -->
            <div class="bg-white dark:bg-gray-900 shadow-sm rounded-lg p-6 animate-on-scroll animate-delay-200">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">How does the multi-clinic system work?</h3>
                <p class="mt-3 text-base text-gray-500 dark:text-gray-400">
                    The platform uses a centralized database architecture. Every record (patients, appointments, billing) is tagged with a specific `clinic_id`. When a user logs in, the application automatically scopes all database queries to their specific clinic, ensuring data isolation.
                </p>
            </div>

            <!-- FAQ Item -->
            <div class="bg-white dark:bg-gray-900 shadow-sm rounded-lg p-6 animate-on-scroll animate-delay-300">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Do I need SSH access to install this?</h3>
                <p class="mt-3 text-base text-gray-500 dark:text-gray-400">
                    No. The system is specifically designed to be deployed without command-line access. You simply upload the files via FTP/cPanel, import the provided SQL file via phpMyAdmin, and update your database credentials in the configuration file.
                </p>
            </div>

            <!-- FAQ Item -->
            <div class="bg-white dark:bg-gray-900 shadow-sm rounded-lg p-6 animate-on-scroll animate-delay-100">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">What is "Bank-Grade Security"?</h3>
                <p class="mt-3 text-base text-gray-500 dark:text-gray-400">
                    It refers to our implementation of rigorous security standards: strong HTTP security headers (CSP, HSTS), PDO prepared statements to block SQL injection, CSRF tokens on all forms, and robust XSS prevention on all output.
                </p>
            </div>
        </div>
    </div>
</div>

<?php require APP_ROOT . '/app/views/inc/tail.php'; ?>
