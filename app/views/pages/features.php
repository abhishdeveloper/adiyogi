<?php require APP_ROOT . '/app/views/inc/head.php'; ?>

<div class="py-16 bg-white dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center animate-on-scroll">
            <h2 class="text-base font-semibold text-primary tracking-wide uppercase">Core Features</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                Everything you need to run your clinic
            </p>
            <p class="mt-4 max-w-2xl text-xl text-gray-500 dark:text-gray-400 mx-auto">
                Our platform provides a comprehensive suite of tools designed specifically for modern medical practices.
            </p>
        </div>

        <div class="mt-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-8 shadow-sm border border-gray-100 dark:border-gray-700 animate-on-scroll animate-delay-100 hover:shadow-md transition-shadow">
                    <div class="text-primary mb-4">
                        <i class="fa-solid fa-hospital-user text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Patient Management</h3>
                    <p class="text-gray-500 dark:text-gray-400">Securely store and manage patient records, medical history, and treatment plans in one centralized location.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-8 shadow-sm border border-gray-100 dark:border-gray-700 animate-on-scroll animate-delay-200 hover:shadow-md transition-shadow">
                    <div class="text-primary mb-4">
                        <i class="fa-solid fa-calendar-check text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Appointment Scheduling</h3>
                    <p class="text-gray-500 dark:text-gray-400">Streamline your booking process with our intuitive calendar interface and automated patient reminders.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-8 shadow-sm border border-gray-100 dark:border-gray-700 animate-on-scroll animate-delay-300 hover:shadow-md transition-shadow">
                    <div class="text-primary mb-4">
                        <i class="fa-solid fa-file-invoice-dollar text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Billing & Invoicing</h3>
                    <p class="text-gray-500 dark:text-gray-400">Generate professional invoices, track payments, and manage financial reporting across multiple clinics.</p>
                </div>

                 <!-- Feature 4 -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-8 shadow-sm border border-gray-100 dark:border-gray-700 animate-on-scroll animate-delay-100 hover:shadow-md transition-shadow">
                    <div class="text-primary mb-4">
                        <i class="fa-solid fa-users-gear text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Staff Access Control</h3>
                    <p class="text-gray-500 dark:text-gray-400">Granular role-based permissions ensure that staff members only access the data required for their specific roles.</p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-8 shadow-sm border border-gray-100 dark:border-gray-700 animate-on-scroll animate-delay-200 hover:shadow-md transition-shadow">
                    <div class="text-primary mb-4">
                        <i class="fa-solid fa-chart-line text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Advanced Analytics</h3>
                    <p class="text-gray-500 dark:text-gray-400">Gain actionable insights into clinic performance, patient demographics, and financial health with interactive dashboards.</p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-8 shadow-sm border border-gray-100 dark:border-gray-700 animate-on-scroll animate-delay-300 hover:shadow-md transition-shadow">
                    <div class="text-primary mb-4">
                        <i class="fa-solid fa-shield-halved text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Data Encryption</h3>
                    <p class="text-gray-500 dark:text-gray-400">Rest easy knowing that all sensitive medical data is encrypted at rest and in transit using industry-standard protocols.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APP_ROOT . '/app/views/inc/tail.php'; ?>
