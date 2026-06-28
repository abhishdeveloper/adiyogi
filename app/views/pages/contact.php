<?php require APP_ROOT . '/app/views/inc/head.php'; ?>

<div class="bg-white dark:bg-gray-900 py-16 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-lg mx-auto md:max-w-none md:grid md:grid-cols-2 md:gap-8">
            <div class="animate-on-scroll">
                <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white sm:text-3xl">
                    Get in Touch
                </h2>
                <div class="mt-3 text-lg text-gray-500 dark:text-gray-400">
                    <p>Have questions about implementing MedClinicPro for your practice? Our team is here to help you understand our security features and deployment process.</p>
                </div>
                <div class="mt-9 border-t border-gray-200 dark:border-gray-700 pt-8">
                    <div class="flex items-center gap-4 text-gray-500 dark:text-gray-400">
                        <i class="fa-solid fa-phone text-primary text-xl"></i>
                        <span class="text-lg">+1 (555) 123-4567</span>
                    </div>
                    <div class="mt-6 flex items-center gap-4 text-gray-500 dark:text-gray-400">
                        <i class="fa-solid fa-envelope text-primary text-xl"></i>
                        <span class="text-lg">support@medclinicpro.com</span>
                    </div>
                    <div class="mt-6 flex items-center gap-4 text-gray-500 dark:text-gray-400">
                        <i class="fa-solid fa-location-dot text-primary text-xl"></i>
                        <span class="text-lg">123 Medical Center Blvd, Suite 100<br>Healthcare City, HC 12345</span>
                    </div>
                </div>
            </div>

            <div class="mt-12 sm:mt-16 md:mt-0 animate-on-scroll animate-delay-200">
                <form action="#" method="POST" class="grid grid-cols-1 gap-y-6 bg-gray-50 dark:bg-gray-800 p-8 rounded-lg shadow-sm">
                    <div>
                        <label for="full-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full name</label>
                        <div class="mt-1">
                            <input type="text" name="full-name" id="full-name" autocomplete="name" class="py-3 px-4 block w-full shadow-sm focus:ring-primary focus:border-primary border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md transition-colors" placeholder="Dr. Jane Doe">
                        </div>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" autocomplete="email" class="py-3 px-4 block w-full shadow-sm focus:ring-primary focus:border-primary border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md transition-colors" placeholder="jane@clinic.com">
                        </div>
                    </div>
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Message</label>
                        <div class="mt-1">
                            <textarea id="message" name="message" rows="4" class="py-3 px-4 block w-full shadow-sm focus:ring-primary focus:border-primary border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md transition-colors" placeholder="How can we help you?"></textarea>
                        </div>
                    </div>
                    <div>
                        <button type="button" class="w-full inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-primary hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APP_ROOT . '/app/views/inc/tail.php'; ?>
