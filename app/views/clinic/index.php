<?php require APP_ROOT . '/app/views/inc/head.php'; ?>

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                    Dashboard: <?php echo htmlspecialchars($data['clinic']->clinic_name); ?>
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage your appointments, profile, and earnings.</p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4 gap-3">
                <?php if(!isset($data['clinic']->staff_role)): // Only Owner sees this ?>
                    <a href="/clinicdashboard/billing" class="hidden lg:inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none transition-colors">
                        <i class="fa-solid fa-file-invoice-dollar mr-2"></i> Billing
                    </a>
                    <a href="/clinicdashboard/doctors" class="hidden lg:inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none transition-colors">
                        <i class="fa-solid fa-user-doctor mr-2"></i> Doctors
                    </a>
                    <a href="/clinicdashboard/staff" class="hidden lg:inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none transition-colors">
                        <i class="fa-solid fa-users-gear mr-2"></i> Staff
                    </a>
                    <a href="/clinicdashboard/profile" class="hidden md:inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-secondary focus:outline-none transition-colors">
                        <i class="fa-solid fa-user-pen mr-2"></i> Edit Profile
                    </a>
                <?php endif; ?>

                <a href="/clinicdashboard/calendar" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none transition-colors">
                    <i class="fa-solid fa-calendar-alt mr-2"></i> Calendar
                </a>
                <a href="/clinicdashboard/book" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none transition-colors">
                    <i class="fa-solid fa-plus mr-2"></i> New Appt
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 mb-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border border-gray-100 dark:border-gray-700 p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900 rounded-md p-3">
                        <i class="fa-solid fa-calendar-check text-primary text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Appointments</dt>
                            <dd class="text-2xl font-semibold text-gray-900 dark:text-white"><?php echo count($data['appointments']); ?></dd>
                        </dl>
                    </div>
                </div>
            </div>

            <?php if(!isset($data['clinic']->staff_role)): // Only Owner sees this ?>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border border-gray-100 dark:border-gray-700 p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 dark:bg-green-900 rounded-md p-3">
                        <i class="fa-solid fa-indian-rupee-sign text-green-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Earnings (Online)</dt>
                            <dd class="text-2xl font-semibold text-gray-900 dark:text-white">₹<?php echo number_format($data['earnings'], 2); ?></dd>
                        </dl>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border border-gray-100 dark:border-gray-700 p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-100 dark:bg-purple-900 rounded-md p-3">
                        <i class="fa-solid fa-link text-purple-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Clinic Code</dt>
                            <dd class="text-lg font-semibold text-gray-900 dark:text-white"><?php echo htmlspecialchars($data['clinic']->unique_code); ?></dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <?php if(!isset($data['clinic']->staff_role)): // Only Owner sees this ?>
        <!-- Analytics Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-5 border border-gray-100 dark:border-gray-700">
                <h3 class="text-base leading-6 font-medium text-gray-900 dark:text-white mb-4">Earnings Overview (Last 6 Months)</h3>
                <div class="relative h-64 w-full">
                    <canvas id="earningsChart"></canvas>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-5 border border-gray-100 dark:border-gray-700">
                <h3 class="text-base leading-6 font-medium text-gray-900 dark:text-white mb-4">Patient Demographics</h3>
                <div class="relative h-64 w-full flex justify-center">
                    <canvas id="demographicsChart"></canvas>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Recent Appointments</h3>
            </div>
            <div class="border-t border-gray-200 dark:border-gray-700">
                <?php if(empty($data['appointments'])): ?>
                    <p class="p-6 text-gray-500 dark:text-gray-400 text-center">No appointments scheduled yet.</p>
                <?php else: ?>
                    <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                        <?php foreach($data['appointments'] as $app): ?>
                            <li class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm font-medium text-primary truncate">
                                        <?php echo htmlspecialchars($app->first_name . ' ' . $app->last_name); ?>
                                    </div>
                                    <div class="ml-2 flex-shrink-0 flex">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            <?php echo $app->status == 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                            <?php echo ucfirst($app->status); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-2 sm:flex sm:justify-between">
                                    <div class="sm:flex">
                                        <p class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                            <i class="fa-regular fa-calendar mr-1.5"></i>
                                            <?php echo date('F j, Y, g:i a', strtotime($app->appointment_datetime)); ?>
                                        </p>
                                    </div>
                                    <div class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400 sm:mt-0">
                                        <i class="fa-solid fa-wallet mr-1.5"></i>
                                        <?php echo ucfirst($app->payment_method); ?> (<?php echo ucfirst($app->payment_status); ?>)
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

<?php if(!isset($data['clinic']->staff_role)): // Only run charts if owner ?>
<!-- Chart.js via CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const monthlyEarningsData = <?php echo json_encode($data['monthly_earnings']); ?>;
    const demographicsData = <?php echo json_encode($data['demographics']); ?>;

    // --- Earnings Chart (Bar) ---
    const earningLabels = monthlyEarningsData.map(item => {
        const d = new Date(item.month_year + '-01');
        return d.toLocaleDateString('default', { month: 'short', year: 'numeric' });
    });
    const earningValues = monthlyEarningsData.map(item => parseFloat(item.total));

    const ctxEarnings = document.getElementById('earningsChart');
    if (ctxEarnings) {
        new Chart(ctxEarnings.getContext('2d'), {
            type: 'bar',
            data: {
                labels: earningLabels.length > 0 ? earningLabels : ['No Data'],
                datasets: [{
                    label: 'Earnings (₹)',
                    data: earningValues.length > 0 ? earningValues : [0],
                    backgroundColor: '#0ea5e9',
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(156, 163, 175, 0.1)' } },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // --- Demographics Chart (Doughnut) ---
    const demoLabels = demographicsData.map(item => item.gender ? item.gender.charAt(0).toUpperCase() + item.gender.slice(1) : 'Unknown');
    const demoValues = demographicsData.map(item => parseInt(item.count));

    const ctxDemo = document.getElementById('demographicsChart');
    if (ctxDemo) {
        new Chart(ctxDemo.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: demoLabels.length > 0 ? demoLabels : ['No Data'],
                datasets: [{
                    data: demoValues.length > 0 ? demoValues : [1],
                    backgroundColor: ['#0ea5e9', '#ec4899', '#8b5cf6', '#cbd5e1'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right' }
                },
                cutout: '70%'
            }
        });
    }
});
</script>
<?php endif; ?>
<?php require APP_ROOT . '/app/views/inc/tail.php'; ?>
