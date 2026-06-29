<?php require APP_ROOT . '/app/views/inc/head.php'; ?>

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                    Manage Presaved Items
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
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">Add New Item</h3>
                <form action="/clinicdashboard/presaved" method="POST" class="space-y-4">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($this->generateCsrfToken()); ?>">
                    <input type="hidden" name="action" value="add">

                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                            <select name="item_type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md dark:bg-gray-700 dark:text-white" required>
                                <option value="medicine">Medicine</option>
                                <option value="diet">Diet Instruction</option>
                                <option value="template">Full Template (JSON)</option>
                            </select>
                        </div>
                        <div class="sm:col-span-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title / Name</label>
                            <input type="text" name="title" class="mt-1 shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white" required>
                        </div>
                        <div class="sm:col-span-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Content</label>
                            <textarea name="content" rows="3" class="mt-1 shadow-sm focus:ring-primary focus:border-primary block w-full sm:text-sm border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white" required placeholder="For medicines: Dosage instructions. For templates: Valid JSON string."></textarea>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                            Add Item
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Existing Items</h3>
            </div>
            <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                <?php if(empty($data['items'])): ?>
                    <li class="px-4 py-4 sm:px-6 text-gray-500 text-sm">No presaved items found.</li>
                <?php endif; ?>
                <?php foreach($data['items'] as $item): ?>
                    <li class="px-4 py-4 sm:px-6 flex items-center justify-between">
                        <div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $item->item_type == 'medicine' ? 'bg-blue-100 text-blue-800' : ($item->item_type == 'diet' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800'); ?> mr-2">
                                <?php echo ucfirst($item->item_type); ?>
                            </span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white"><?php echo htmlspecialchars($item->title); ?></span>
                            <p class="mt-1 text-xs text-gray-500 truncate max-w-2xl"><?php echo htmlspecialchars($item->content); ?></p>
                        </div>
                        <form action="/clinicdashboard/presaved" method="POST" class="ml-4" onsubmit="return confirm('Are you sure?');">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($this->generateCsrfToken()); ?>">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="item_id" value="<?php echo $item->id; ?>">
                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

    </div>
</div>

<?php require APP_ROOT . '/app/views/inc/tail.php'; ?>
