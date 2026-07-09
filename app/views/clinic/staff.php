<?php require APP_ROOT . '/app/views/inc/head.php'; ?>

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300 pb-safe">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                    Staff Management
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Add receptionists or nurses to help manage your clinic.</p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="/clinicdashboard/index" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    Back to Dashboard
                </a>
            </div>
        </div>

        <?php if(!empty($data['success_msg'])): ?>
            <div class="rounded-md bg-green-50 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0"><i class="fa-solid fa-circle-check text-green-400"></i></div>
                    <div class="ml-3"><p class="text-sm font-medium text-green-800"><?php echo htmlspecialchars($data['success_msg']); ?></p></div>
                </div>
            </div>
        <?php endif; ?>
        <?php if(!empty($data['error_msg'])): ?>
            <div class="rounded-md bg-red-50 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0"><i class="fa-solid fa-triangle-exclamation text-red-400"></i></div>
                    <div class="ml-3"><p class="text-sm font-medium text-red-800"><?php echo htmlspecialchars($data['error_msg']); ?></p></div>
                </div>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Add New Staff Member</h3>
                    </div>
                    <div class="p-6">
                        <form action="/clinicdashboard/staff" method="POST" class="space-y-4">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($this->generateCsrfToken()); ?>">
                            <input type="hidden" name="action" value="add">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">First Name</label>
                                <input type="text" name="first_name" required class="mt-1 block w-full shadow-sm focus:ring-primary focus:border-primary sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Last Name</label>
                                <input type="text" name="last_name" required class="mt-1 block w-full shadow-sm focus:ring-primary focus:border-primary sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address (Login ID)</label>
                                <input type="email" name="email" required class="mt-1 block w-full shadow-sm focus:ring-primary focus:border-primary sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                                <input type="password" name="password" required class="mt-1 block w-full shadow-sm focus:ring-primary focus:border-primary sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                                <select name="staff_role" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md dark:bg-gray-700 dark:text-white">
                                    <option value="receptionist">Receptionist (Can book & view calendar)</option>
                                    <option value="nurse">Nurse (Can access medical details)</option>
                                </select>
                            </div>
                            <div class="pt-2 border-t border-gray-200 dark:border-gray-700 mt-6">
                                <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-secondary focus:outline-none transition-colors">
                                    Create Staff Account
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Active Staff Accounts</h3>
                    </div>

                    <?php if(empty($data['staff'])): ?>
                        <div class="p-6 text-center text-gray-500 text-sm">No staff members have been added yet.</div>
                    <?php else: ?>
                        <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                            <?php foreach($data['staff'] as $staff): ?>
                                <li class="p-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-primary font-bold">
                                                <?php echo strtoupper(substr($staff->first_name, 0, 1) . substr($staff->last_name, 0, 1)); ?>
                                            </div>
                                            <div class="ml-4">
                                                <p class="text-sm font-medium text-gray-900 dark:text-white"><?php echo htmlspecialchars($staff->first_name . ' ' . $staff->last_name); ?></p>
                                                <p class="text-xs text-gray-500"><?php echo htmlspecialchars($staff->email); ?></p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 capitalize">
                                                <?php echo htmlspecialchars($staff->staff_role); ?>
                                            </span>
                                            <form action="/clinicdashboard/staff" method="POST" onsubmit="return confirm('Are you sure you want to remove this staff member? They will lose all access immediately.');">
                                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($this->generateCsrfToken()); ?>">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="staff_user_id" value="<?php echo $staff->user_id; ?>">
                                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">Remove</button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<?php require APP_ROOT . '/app/views/inc/tail.php'; ?>
