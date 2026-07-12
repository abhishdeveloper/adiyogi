<?php require APP_ROOT . '/app/views/inc/header.php'; ?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-4 sm:px-0">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                    Doctor Consultation
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Clinic: <?php echo htmlspecialchars($data['appointment']->clinic_name ?? 'Doctor'); ?>
                </p>
            </div>
            <div>
                <a href="/patientdashboard/index" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                    <i class="fa-solid fa-phone-slash mr-2"></i> Leave Room
                </a>
            </div>
        </div>

        <div class="bg-black shadow rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 h-[600px] relative">
            <div id="meet" class="w-full h-full absolute inset-0"></div>
        </div>
    </div>
</div>

<script src='https://meet.jit.si/external_api.js'></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const domain = 'meet.jit.si';
        const options = {
            roomName: 'MedClinicPro_<?php echo htmlspecialchars($data['appointment']->meeting_room); ?>',
            width: '100%',
            height: '100%',
            parentNode: document.querySelector('#meet'),
            userInfo: {
                displayName: '<?php echo addslashes($_SESSION['user_name'] ?? 'Patient'); ?>'
            },
            configOverwrite: {
                prejoinPageEnabled: true,
                disableDeepLinking: true
            },
            interfaceConfigOverwrite: {
                TOOLBAR_BUTTONS: [
                    'microphone', 'camera', 'chat', 'raisehand',
                    'videoquality', 'tileview', 'hangup'
                ],
            }
        };
        const api = new JitsiMeetExternalAPI(domain, options);
    });
</script>

<?php require APP_ROOT . '/app/views/inc/tail.php'; ?>
