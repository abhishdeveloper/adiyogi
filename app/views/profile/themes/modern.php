<?php require APP_ROOT . '/app/views/inc/head.php'; ?>

<!-- Modern Theme: Clean, white/gray background, vibrant accents, centered layout -->
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-white dark:from-gray-900 dark:to-gray-800 transition-colors duration-300 py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-gray-700 animate-on-scroll">

            <!-- Header Banner -->
            <div class="h-32 bg-primary"></div>

            <div class="relative px-6 pb-8 text-center -mt-16">
                <!-- Profile Image -->
                <?php if($data['clinic']->profile_image): ?>
                    <img src="/assets/uploads/profiles/<?php echo htmlspecialchars($data['clinic']->profile_image); ?>" alt="Profile" class="w-32 h-32 rounded-full mx-auto border-4 border-white dark:border-gray-800 object-cover shadow-md bg-white">
                <?php else: ?>
                    <div class="w-32 h-32 rounded-full mx-auto border-4 border-white dark:border-gray-800 bg-gray-100 dark:bg-gray-700 flex items-center justify-center shadow-md">
                        <i class="fa-solid fa-user-doctor text-gray-400 text-5xl"></i>
                    </div>
                <?php endif; ?>

                <!-- Name & Title -->
                <h1 class="mt-4 text-3xl font-extrabold text-gray-900 dark:text-white"><?php echo htmlspecialchars($data['clinic']->clinic_name); ?></h1>
                <p class="mt-1 text-lg font-medium text-secondary"><?php echo htmlspecialchars($data['clinic']->specialty); ?></p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1"><?php echo htmlspecialchars($data['clinic']->degrees); ?></p>

                <!-- Quick Actions -->
                <div class="mt-6 flex justify-center gap-4">
                    <a href="tel:<?php echo htmlspecialchars($data['clinic']->phone); ?>" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-full text-white bg-primary hover:bg-secondary focus:outline-none transition-colors">
                        <i class="fa-solid fa-phone mr-2"></i> Call Now
                    </a>
                    <a href="/patient/book/<?php echo htmlspecialchars($data['clinic']->id); ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-full text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fa-solid fa-calendar-check mr-2"></i> Book Appointment
                    </a>
                </div>
            </div>

            <!-- Content Area -->
            <div class="px-6 py-6 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-700 text-left">

                <?php if(!empty($data['clinic']->bio)): ?>
                <div class="mb-6">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-2">About Us</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed whitespace-pre-line"><?php echo htmlspecialchars($data['clinic']->bio); ?></p>
                </div>
                <?php endif; ?>

                <?php if(!empty($data['clinic']->achievements)): ?>
                <div class="mb-6">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-2"><i class="fa-solid fa-award text-yellow-500 mr-2"></i> Achievements</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-sm"><?php echo htmlspecialchars($data['clinic']->achievements); ?></p>
                </div>
                <?php endif; ?>

                <div class="mb-6">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-2">Location & Contact</h3>
                    <div class="flex items-start text-sm text-gray-600 dark:text-gray-300 mb-2">
                        <i class="fa-solid fa-location-dot mt-1 mr-3 text-primary w-4"></i>
                        <span><?php echo htmlspecialchars($data['clinic']->address . ', ' . $data['clinic']->city . ', ' . $data['clinic']->state . ' ' . $data['clinic']->zip); ?></span>
                    </div>
                    <?php if(!empty($data['clinic']->phone)): ?>
                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                        <i class="fa-solid fa-phone mr-3 text-primary w-4"></i>
                        <span><?php echo htmlspecialchars($data['clinic']->phone); ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Social Links Grid -->
                <?php if(array_filter($data['socials'])): ?>
                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 flex justify-center gap-6">
                    <?php if(!empty($data['socials']['instagram'])): ?>
                        <a href="<?php echo htmlspecialchars($data['socials']['instagram']); ?>" target="_blank" class="text-gray-400 hover:text-pink-600 transition-colors">
                            <span class="sr-only">Instagram</span>
                            <i class="fa-brands fa-instagram text-2xl"></i>
                        </a>
                    <?php endif; ?>
                    <?php if(!empty($data['socials']['facebook'])): ?>
                        <a href="<?php echo htmlspecialchars($data['socials']['facebook']); ?>" target="_blank" class="text-gray-400 hover:text-blue-600 transition-colors">
                            <span class="sr-only">Facebook</span>
                            <i class="fa-brands fa-facebook text-2xl"></i>
                        </a>
                    <?php endif; ?>
                    <?php if(!empty($data['socials']['linkedin'])): ?>
                        <a href="<?php echo htmlspecialchars($data['socials']['linkedin']); ?>" target="_blank" class="text-gray-400 hover:text-blue-800 transition-colors">
                            <span class="sr-only">LinkedIn</span>
                            <i class="fa-brands fa-linkedin text-2xl"></i>
                        </a>
                    <?php endif; ?>
                    <?php if(!empty($data['socials']['website'])): ?>
                        <a href="<?php echo htmlspecialchars($data['socials']['website']); ?>" target="_blank" class="text-gray-400 hover:text-gray-800 dark:hover:text-white transition-colors">
                            <span class="sr-only">Website</span>
                            <i class="fa-solid fa-globe text-2xl"></i>
                        </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

            </div>
        </div>

    </div>
</div>

<?php require APP_ROOT . '/app/views/inc/tail.php'; ?>
