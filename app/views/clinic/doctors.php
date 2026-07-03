<?php require APP_ROOT . '/app/views/inc/head.php'; ?>

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300 pb-safe">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                    Manage Clinic Doctors
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="/clinicdashboard/index" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    Back to Dashboard
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg border border-gray-200 dark:border-gray-700 mb-8">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Add New Doctor</h3>
                <form action="/clinicdashboard/doctors" method="POST" enctype="multipart/form-data" class="space-y-4">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($this->generateCsrfToken()); ?>">
                    <input type="hidden" name="action" value="add">

                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                            <input type="text" name="name" class="mt-1 shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white" required placeholder="Dr. Jane Smith">
                        </div>
                        <div class="sm:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Specialty</label>
                            <input type="text" name="specialty" class="mt-1 shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white" placeholder="Orthopedics">
                        </div>
                        <div class="sm:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Qualifications</label>
                            <input type="text" name="qualifications" class="mt-1 shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white" placeholder="MBBS, MS">
                        </div>
                        <div class="sm:col-span-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Photo</label>
                            <input type="file" name="photo" accept=".jpg,.jpeg,.png" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-secondary">
                        </div>
                        <div class="sm:col-span-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bio</label>
                            <textarea name="bio" rows="2" class="mt-1 shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white"></textarea>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-secondary focus:outline-none transition-colors">
                            Add Doctor
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Existing Doctors</h3>
            </div>
            <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                <?php if(empty($data['doctors'])): ?>
                    <li class="px-4 py-4 sm:px-6 text-gray-500 text-sm">No additional doctors added yet.</li>
                <?php endif; ?>
                <?php foreach($data['doctors'] as $doctor): ?>
                    <li class="px-4 py-4 sm:px-6 flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                                <?php if($doctor->photo): ?>
                                    <img src="/assets/uploads/doctors/<?php echo htmlspecialchars($doctor->photo); ?>" alt="" class="h-full w-full object-cover">
                                <?php else: ?>
                                    <i class="fa-solid fa-user-doctor text-gray-400 mt-3 ml-3 text-xl"></i>
                                <?php endif; ?>
                            </div>
                            <div class="ml-4">
                                <span class="text-sm font-medium text-gray-900 dark:text-white"><?php echo htmlspecialchars($doctor->name); ?></span>
                                <p class="text-xs text-gray-500"><?php echo htmlspecialchars($doctor->specialty . ' | ' . $doctor->qualifications); ?></p>
                            </div>
                        </div>
                        <form action="/clinicdashboard/doctors" method="POST" class="ml-4" onsubmit="return confirm('Remove this doctor?');">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($this->generateCsrfToken()); ?>">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="doctor_id" value="<?php echo $doctor->id; ?>">
                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Remove</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>

<?php require APP_ROOT . '/app/views/inc/tail.php'; ?>
