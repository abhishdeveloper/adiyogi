<?php require APP_ROOT . '/app/views/inc/head.php'; ?>

<!-- Hero Section -->
<div class="relative bg-white dark:bg-gray-900 overflow-hidden transition-colors duration-300">
    <div class="max-w-7xl mx-auto">
        <div class="relative z-10 pb-8 bg-white dark:bg-gray-900 sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32 transition-colors duration-300">
            <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                <div class="sm:text-center lg:text-left animate-on-scroll">
                    <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white sm:text-5xl md:text-6xl">
                        <span class="block xl:inline">Bank-Grade Security for</span>
                        <span class="block text-primary">Your Medical Clinic</span>
                    </h1>
                    <p class="mt-3 text-base text-gray-500 dark:text-gray-400 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                        Manage your multi-clinic operations with our ultra-secure, scalable platform. Protect patient data while streamlining your workflow.
                    </p>
                    <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                        <div class="rounded-md shadow">
                            <a href="/auth/register_choice" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary hover:bg-secondary md:py-4 md:text-lg transition-colors">
                                Get Started
                            </a>
                        </div>
                        <div class="mt-3 sm:mt-0 sm:ml-3">
                            <a href="/pages/features" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-primary bg-blue-100 hover:bg-blue-200 dark:bg-gray-800 dark:text-primary dark:hover:bg-gray-700 md:py-4 md:text-lg transition-colors">
                                View Features
                            </a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <!-- Abstract graphic for hero -->
    <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 bg-blue-50 dark:bg-gray-800 flex items-center justify-center transition-colors duration-300">
        <div class="p-12 text-center animate-on-scroll animate-delay-200">
             <i class="fa-solid fa-shield-heart text-9xl text-primary opacity-80"></i>
             <div class="mt-8 grid grid-cols-2 gap-4">
                 <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow-sm">
                     <i class="fa-solid fa-user-doctor text-2xl text-secondary mb-2"></i>
                     <h3 class="font-bold dark:text-white">Multi-Clinic</h3>
                 </div>
                 <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow-sm">
                     <i class="fa-solid fa-lock text-2xl text-secondary mb-2"></i>
                     <h3 class="font-bold dark:text-white">HIPAA Ready</h3>
                 </div>
             </div>
        </div>
    </div>
</div>

<!-- Quick Feature Section -->
<div class="py-12 bg-gray-50 dark:bg-gray-800 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:text-center animate-on-scroll">
            <h2 class="text-base text-primary font-semibold tracking-wide uppercase">Foundation</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                Built for Scale and Security
            </p>
        </div>

        <div class="mt-10">
            <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-3 md:gap-x-8 md:gap-y-10">

                <div class="relative animate-on-scroll animate-delay-100">
                    <dt>
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-primary text-white">
                            <i class="fa-solid fa-server text-xl"></i>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-medium text-gray-900 dark:text-white">Shared Hosting Optimized</p>
                    </dt>
                    <dd class="mt-2 ml-16 text-base text-gray-500 dark:text-gray-400">
                        Designed to run flawlessly on standard environments without requiring complex server administration.
                    </dd>
                </div>

                <div class="relative animate-on-scroll animate-delay-200">
                    <dt>
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-primary text-white">
                            <i class="fa-solid fa-sitemap text-xl"></i>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-medium text-gray-900 dark:text-white">MVC Architecture</p>
                    </dt>
                    <dd class="mt-2 ml-16 text-base text-gray-500 dark:text-gray-400">
                        Clean separation of concerns ensuring code is maintainable, scalable, and secure.
                    </dd>
                </div>

                <div class="relative animate-on-scroll animate-delay-300">
                    <dt>
                        <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-primary text-white">
                            <i class="fa-solid fa-user-shield text-xl"></i>
                        </div>
                        <p class="ml-16 text-lg leading-6 font-medium text-gray-900 dark:text-white">Bank-Grade Protection</p>
                    </dt>
                    <dd class="mt-2 ml-16 text-base text-gray-500 dark:text-gray-400">
                        Implementation of strict security headers, CSRF protection, and SQL injection prevention by default.
                    </dd>
                </div>

            </dl>
        </div>
    </div>
</div>

<?php require APP_ROOT . '/app/views/inc/tail.php'; ?>
