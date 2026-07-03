<nav class="bg-white dark:bg-gray-900 shadow-md sticky top-0 z-50 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="/" class="flex-shrink-0 flex items-center gap-2">
                    <i class="fa-solid fa-staff-snake text-primary text-2xl"></i>
                    <span class="font-bold text-xl tracking-tight text-gray-900 dark:text-white">MedClinic<span class="text-primary">Pro</span></span>
                </a>
                <div class="hidden md:ml-6 md:flex md:space-x-8">
                    <a href="/" class="border-transparent text-gray-500 dark:text-gray-300 hover:border-primary hover:text-gray-900 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">Home</a>
                    <a href="/pages/services" class="border-transparent text-gray-500 dark:text-gray-300 hover:border-primary hover:text-gray-900 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">Services</a>
                    <a href="/pages/features" class="border-transparent text-gray-500 dark:text-gray-300 hover:border-primary hover:text-gray-900 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">Features</a>
                    <a href="/pages/faq" class="border-transparent text-gray-500 dark:text-gray-300 hover:border-primary hover:text-gray-900 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">FAQ</a>
                    <a href="/pages/contact" class="border-transparent text-gray-500 dark:text-gray-300 hover:border-primary hover:text-gray-900 dark:hover:text-white inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">Contact</a>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <button id="theme-toggle" type="button" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5 transition-colors">
                    <i id="theme-toggle-dark-icon" class="hidden fa-solid fa-moon"></i>
                    <i id="theme-toggle-light-icon" class="hidden fa-solid fa-sun"></i>
                </button>

                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="<?php echo $_SESSION['user_role'] == 'clinic' ? '/clinicdashboard' : '/patientdashboard'; ?>" class="hidden md:inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors shadow-sm">
                        Dashboard
                    </a>
                <?php else: ?>
                    <a href="/auth/login" class="hidden md:inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm">
                        Login
                    </a>
                    <a href="/auth/register_choice" class="hidden md:inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors shadow-sm">
                        Register
                    </a>
                <?php endif; ?>

                <div class="flex items-center md:hidden">
                    <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</nav>
