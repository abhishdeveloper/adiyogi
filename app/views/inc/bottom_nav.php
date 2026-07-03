<?php
$current_url = $_SERVER['REQUEST_URI'];
?>
<div class="fixed bottom-0 left-0 z-50 w-full h-16 bg-white border-t border-gray-200 dark:bg-gray-800 dark:border-gray-700 sm:hidden pb-safe">
    <div class="grid h-full max-w-lg grid-cols-4 mx-auto font-medium">
        <a href="/" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-900 group <?php echo ($current_url == '/' || $current_url == '/pages/index') ? 'text-primary' : 'text-gray-500 dark:text-gray-400'; ?>">
            <i class="fa-solid fa-house w-6 h-6 mb-1 text-lg group-hover:text-primary"></i>
            <span class="text-xs group-hover:text-primary">Home</span>
        </a>

        <?php if(isset($_SESSION['user_id'])): ?>
            <?php if($_SESSION['user_role'] == 'patient'): ?>
                <a href="/patientdashboard" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-900 group <?php echo strpos($current_url, '/patientdashboard') !== false ? 'text-primary' : 'text-gray-500 dark:text-gray-400'; ?>">
                    <i class="fa-solid fa-user w-6 h-6 mb-1 text-lg group-hover:text-primary"></i>
                    <span class="text-xs group-hover:text-primary">Dashboard</span>
                </a>
            <?php elseif($_SESSION['user_role'] == 'clinic'): ?>
                <a href="/clinicdashboard" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-900 group <?php echo strpos($current_url, '/clinicdashboard') !== false ? 'text-primary' : 'text-gray-500 dark:text-gray-400'; ?>">
                    <i class="fa-solid fa-user-doctor w-6 h-6 mb-1 text-lg group-hover:text-primary"></i>
                    <span class="text-xs group-hover:text-primary">Dashboard</span>
                </a>
            <?php endif; ?>
        <?php else: ?>
            <a href="/auth/login" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-900 group <?php echo strpos($current_url, '/auth/login') !== false ? 'text-primary' : 'text-gray-500 dark:text-gray-400'; ?>">
                <i class="fa-solid fa-arrow-right-to-bracket w-6 h-6 mb-1 text-lg group-hover:text-primary"></i>
                <span class="text-xs group-hover:text-primary">Login</span>
            </a>
        <?php endif; ?>

        <a href="/pages/features" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-900 group <?php echo strpos($current_url, '/pages/features') !== false ? 'text-primary' : 'text-gray-500 dark:text-gray-400'; ?>">
            <i class="fa-solid fa-star w-6 h-6 mb-1 text-lg group-hover:text-primary"></i>
            <span class="text-xs group-hover:text-primary">Features</span>
        </a>

        <a href="/pages/contact" class="inline-flex flex-col items-center justify-center px-5 hover:bg-gray-50 dark:hover:bg-gray-900 group <?php echo strpos($current_url, '/pages/contact') !== false ? 'text-primary' : 'text-gray-500 dark:text-gray-400'; ?>">
            <i class="fa-solid fa-envelope w-6 h-6 mb-1 text-lg group-hover:text-primary"></i>
            <span class="text-xs group-hover:text-primary">Contact</span>
        </a>
    </div>
</div>
