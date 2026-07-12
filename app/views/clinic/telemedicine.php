<?php require APP_ROOT . '/app/views/inc/header.php'; ?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-4 sm:px-0">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                    Telemedicine Session
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Patient: <?php echo htmlspecialchars($data['appointment']->patient_first . ' ' . $data['appointment']->patient_last); ?>
                </p>
            </div>
            <div>
                <a href="/clinicdashboard/attend/<?php echo $data['appointment']->id; ?>" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    <i class="fa-solid fa-notes-medical mr-2"></i> Write Prescription
                </a>
                <a href="/clinicdashboard/index" class="ml-2 inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50">
                    End Call & Exit
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 h-[600px]">
            <div id="meet" class="w-full h-full"></div>
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
                displayName: 'Dr. <?php echo addslashes($data['clinic']->clinic_name); ?>'
            },
            configOverwrite: {
                prejoinPageEnabled: false,
                disableDeepLinking: true
            },
            interfaceConfigOverwrite: {
                TOOLBAR_BUTTONS: [
                    'microphone', 'camera', 'closedcaptions', 'desktop', 'fullscreen',
                    'fodeviceselection', 'hangup', 'profile', 'chat', 'recording',
                    'livestreaming', 'etherpad', 'sharedvideo', 'settings', 'raisehand',
                    'videoquality', 'filmstrip', 'feedback', 'stats', 'shortcuts',
                    'tileview', 'videobackgroundblur', 'download', 'help', 'mute-everyone', 'security'
                ],
            }
        };
        const api = new JitsiMeetExternalAPI(domain, options);
    });
</script>

<?php require APP_ROOT . '/app/views/inc/tail.php'; ?>
