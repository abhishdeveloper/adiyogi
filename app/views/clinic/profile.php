<?php require APP_ROOT . '/app/views/inc/head.php'; ?>

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                    Edit Public Profile
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Design your digital visiting card.</p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="/clinicdashboard/index" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 transition-colors">
                    Back to Dashboard
                </a>
            </div>
        </div>

        <?php if(!empty($data['success_msg'])): ?>
            <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-md shadow-sm">
                <p class="text-sm text-green-700"><?php echo $data['success_msg']; ?></p>
            </div>
        <?php endif; ?>
        <?php if(!empty($data['error_msg'])): ?>
            <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-md shadow-sm">
                <p class="text-sm text-red-700"><?php echo $data['error_msg']; ?></p>
            </div>
        <?php endif; ?>

        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="px-4 py-5 sm:p-6">
                <form action="/clinicdashboard/profile" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($this->generateCsrfToken()); ?>">

                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">

                        <div class="sm:col-span-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Profile / Clinic Image</label>
                            <div class="mt-2 flex items-center gap-4">
                                <?php if($data['clinic']->profile_image): ?>
                                    <img src="/assets/uploads/profiles/<?php echo htmlspecialchars($data['clinic']->profile_image); ?>" class="h-16 w-16 rounded-full object-cover border">
                                <?php else: ?>
                                    <span class="h-16 w-16 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                        <i class="fa-solid fa-user-doctor text-gray-300 text-3xl"></i>
                                    </span>
                                <?php endif; ?>
                                <input type="file" name="profile_image" accept="image/*" class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-primary hover:file:bg-blue-100 cursor-pointer">
                            </div>
                        </div>

                        <div class="sm:col-span-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Theme Preference</label>
                            <select name="theme_preference" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md dark:bg-gray-700 dark:text-white">
                                <option value="modern" <?php echo $data['clinic']->theme_preference == 'modern' ? 'selected' : ''; ?>>Modern & Clean</option>
                                <option value="classic" <?php echo $data['clinic']->theme_preference == 'classic' ? 'selected' : ''; ?>>Classic Medical</option>
                                <option value="minimal" <?php echo $data['clinic']->theme_preference == 'minimal' ? 'selected' : ''; ?>>Dark Minimalist</option>
                            </select>
                        </div>

                        <div class="sm:col-span-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Professional Bio</label>
                            <textarea name="bio" rows="3" class="mt-1 block w-full shadow-sm focus:ring-primary focus:border-primary sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white"><?php echo htmlspecialchars($data['clinic']->bio ?? ''); ?></textarea>
                        </div>

                        <div class="sm:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Degrees & Qualifications</label>
                            <input type="text" name="degrees" placeholder="e.g. MBBS, MD (Cardiology)" value="<?php echo htmlspecialchars($data['clinic']->degrees ?? ''); ?>" class="mt-1 block w-full shadow-sm focus:ring-primary focus:border-primary sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                        </div>

                        <div class="sm:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Achievements / Awards</label>
                            <input type="text" name="achievements" placeholder="e.g. Best Clinic 2023" value="<?php echo htmlspecialchars($data['clinic']->achievements ?? ''); ?>" class="mt-1 block w-full shadow-sm focus:ring-primary focus:border-primary sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                        </div>

                        <div class="sm:col-span-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">Social & Contact Links</h4>
                        </div>

                        <div class="sm:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Instagram Username/URL</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-600 text-gray-500 dark:text-gray-300 sm:text-sm"><i class="fa-brands fa-instagram"></i></span>
                                <input type="text" name="instagram" value="<?php echo htmlspecialchars($data['socials']['instagram'] ?? ''); ?>" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md focus:ring-primary focus:border-primary sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Facebook URL</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-600 text-gray-500 dark:text-gray-300 sm:text-sm"><i class="fa-brands fa-facebook-f"></i></span>
                                <input type="text" name="facebook" value="<?php echo htmlspecialchars($data['socials']['facebook'] ?? ''); ?>" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md focus:ring-primary focus:border-primary sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">LinkedIn URL</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-600 text-gray-500 dark:text-gray-300 sm:text-sm"><i class="fa-brands fa-linkedin-in"></i></span>
                                <input type="text" name="linkedin" value="<?php echo htmlspecialchars($data['socials']['linkedin'] ?? ''); ?>" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md focus:ring-primary focus:border-primary sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Website URL</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-600 text-gray-500 dark:text-gray-300 sm:text-sm"><i class="fa-solid fa-globe"></i></span>
                                <input type="text" name="website" value="<?php echo htmlspecialchars($data['socials']['website'] ?? ''); ?>" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md focus:ring-primary focus:border-primary sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>
                    </div>

                    <div class="pt-5 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex justify-end">
                            <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-secondary focus:outline-none transition-colors">
                                Save Profile
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<?php require APP_ROOT . '/app/views/inc/tail.php'; ?>
