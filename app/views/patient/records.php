<?php require APP_ROOT . '/app/views/inc/head.php'; ?>

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300 pb-safe">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                    Medical Records
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="/patientdashboard/index" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
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

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

            <!-- Past Prescriptions -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 rounded-t-lg">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                        <i class="fa-solid fa-file-prescription text-primary mr-2"></i> Clinic Prescriptions
                    </h3>
                </div>
                <div class="p-0">
                    <?php if(empty($data['prescriptions'])): ?>
                        <div class="p-6 text-center text-gray-500 text-sm">No past prescriptions found.</div>
                    <?php else: ?>
                        <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                            <?php foreach($data['prescriptions'] as $presc): ?>
                                <li class="p-4 hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-bold text-gray-900 dark:text-white"><?php echo htmlspecialchars($presc->clinic_name); ?></p>
                                            <p class="text-xs text-gray-500 mt-1"><?php echo date('F j, Y', strtotime($presc->appointment_datetime)); ?></p>
                                        </div>
                                        <div>
                                            <a href="/patientdashboard/attend/<?php echo $presc->appointment_id; ?>" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-primary hover:bg-secondary focus:outline-none transition-colors">
                                                View & Download
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Uploaded Lab Reports -->
            <div>
                <!-- Upload Form -->
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg border border-gray-200 dark:border-gray-700 mb-6">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Upload External Report</h3>
                    </div>
                    <div class="p-4 sm:p-6">
                        <form action="/patientdashboard/records" method="POST" enctype="multipart/form-data" class="space-y-4">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($this->generateCsrfToken()); ?>">
                            <input type="hidden" name="action" value="upload">

                            <div class="grid grid-cols-1 gap-y-4 gap-x-4 sm:grid-cols-2">
                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Report Title</label>
                                    <input type="text" name="title" required placeholder="e.g. Blood Test - Complete Hemogram" class="mt-1 block w-full shadow-sm focus:ring-primary focus:border-primary sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date of Report</label>
                                    <input type="date" name="report_date" required class="mt-1 block w-full shadow-sm focus:ring-primary focus:border-primary sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">File (PDF/Image)</label>
                                    <input type="file" name="report_file" required accept=".pdf,.jpg,.jpeg,.png" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-secondary">
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes (Optional)</label>
                                    <textarea name="notes" rows="2" class="mt-1 block w-full shadow-sm focus:ring-primary focus:border-primary sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white"></textarea>
                                </div>
                            </div>
                            <div class="text-right">
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none transition-colors">
                                    Upload Document
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Reports List -->
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 rounded-t-lg">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            <i class="fa-solid fa-file-waveform text-green-500 mr-2"></i> My Uploaded Reports
                        </h3>
                    </div>
                    <div class="p-0">
                        <?php if(empty($data['reports'])): ?>
                            <div class="p-6 text-center text-gray-500 text-sm">No external reports uploaded.</div>
                        <?php else: ?>
                            <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                                <?php foreach($data['reports'] as $report): ?>
                                    <li class="p-4 hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors flex items-center justify-between">
                                        <div class="flex items-start">
                                            <div class="mt-1">
                                                <?php if(str_ends_with($report->file_path, '.pdf')): ?>
                                                    <i class="fa-solid fa-file-pdf text-red-500 text-2xl"></i>
                                                <?php else: ?>
                                                    <i class="fa-solid fa-file-image text-blue-500 text-2xl"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-bold text-gray-900 dark:text-white"><?php echo htmlspecialchars($report->title); ?></p>
                                                <p class="text-xs text-gray-500">Date: <?php echo date('M j, Y', strtotime($report->report_date)); ?></p>
                                                <?php if($report->notes): ?>
                                                    <p class="text-[10px] text-gray-400 mt-1 italic"><?php echo htmlspecialchars($report->notes); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="flex flex-col items-end gap-2">
                                            <a href="/assets/uploads/reports/<?php echo htmlspecialchars($report->file_path); ?>" target="_blank" class="text-primary hover:text-secondary text-sm font-medium">
                                                View
                                            </a>
                                            <form action="/patientdashboard/records" method="POST" onsubmit="return confirm('Delete this report?');">
                                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($this->generateCsrfToken()); ?>">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="report_id" value="<?php echo $report->id; ?>">
                                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs">Delete</button>
                                            </form>
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
</div>

<?php require APP_ROOT . '/app/views/inc/tail.php'; ?>
