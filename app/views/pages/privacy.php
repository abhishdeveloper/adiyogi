<?php require APP_ROOT . '/app/views/inc/head.php'; ?>

<div class="bg-white dark:bg-gray-900 py-16 transition-colors duration-300 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="prose prose-blue dark:prose-invert max-w-none animate-on-scroll">
            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white sm:text-4xl mb-8">Privacy Policy</h1>

            <p class="text-gray-500 dark:text-gray-400 mb-6">Last updated: <?= date('F j, Y') ?></p>

            <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">1. Introduction</h2>
            <p class="text-gray-500 dark:text-gray-400 mb-6">
                MedClinicPro ("we", "our", or "us") is committed to protecting your privacy and ensuring the security of your personal and medical data. This Privacy Policy outlines how we collect, use, disclose, and safeguard your information when you visit our website or use our multi-clinic management system.
            </p>

            <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">2. Data Security on Shared Hosting</h2>
            <p class="text-gray-500 dark:text-gray-400 mb-6">
                We understand the unique challenges of operating on shared hosting environments. To mitigate risks, we employ:
                <ul class="list-disc pl-5 mt-2 space-y-1">
                    <li>Strict application-level routing to prevent direct file access.</li>
                    <li>Robust security headers to prevent Cross-Site Scripting (XSS) and clickjacking.</li>
                    <li>Prepared SQL statements to eliminate SQL injection vulnerabilities.</li>
                    <li>Logical data isolation using specific clinic identifiers for all database transactions.</li>
                </ul>
            </p>

            <h2 class="text-xl font-bold text-gray-900 dark:text-white mt-8 mb-4">3. Information Collection</h2>
            <p class="text-gray-500 dark:text-gray-400 mb-6">
                When you register a clinic or contact us, we collect necessary administrative information (name, email, clinic details). We do not sell this information to third parties.
            </p>

            <p class="text-gray-500 dark:text-gray-400 mt-12 text-sm">
                * This is a boilerplate privacy policy for demonstration purposes. Before deploying your application to production, you must consult with legal counsel to ensure compliance with HIPAA, GDPR, or other relevant local healthcare and data protection regulations.
            </p>
        </div>
    </div>
</div>

<?php require APP_ROOT . '/app/views/inc/tail.php'; ?>
