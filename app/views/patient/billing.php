<?php require APP_ROOT . '/app/views/inc/head.php'; ?>

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300 pb-safe">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                    Billing History
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="/patientdashboard/index" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    Back to Dashboard
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md border border-gray-200 dark:border-gray-700">
            <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                <?php if(empty($data['invoices'])): ?>
                    <li class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                        No billing history found.
                    </li>
                <?php else: ?>
                    <?php foreach($data['invoices'] as $invoice): ?>
                        <li>
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-primary truncate">
                                        Invoice #<?php echo htmlspecialchars($invoice->id); ?>
                                    </p>
                                    <div class="ml-2 flex-shrink-0 flex">
                                        <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
                                            Paid
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-2 sm:flex sm:justify-between">
                                    <div class="sm:flex">
                                        <p class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                            ₹<?php echo number_format($invoice->amount, 2); ?>
                                        </p>
                                    </div>
                                    <div class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400 sm:mt-0">
                                        <i class="fa-regular fa-calendar mr-1.5 text-gray-400"></i>
                                        <p>
                                            <?php echo date('M j, Y', strtotime($invoice->created_at)); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

<?php require APP_ROOT . '/app/views/inc/tail.php'; ?>
