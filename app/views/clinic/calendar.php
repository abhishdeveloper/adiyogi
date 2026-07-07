<?php require APP_ROOT . '/app/views/inc/head.php'; ?>

<!-- FullCalendar CSS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300 pb-safe">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                    Scheduling Calendar
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Drag appointments to reschedule. Click empty slots to block time.</p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4 gap-3">
                <a href="/clinicdashboard/index" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    Back to Dashboard
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div id="calendar" class="min-h-[600px] text-gray-900 dark:text-white"></div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    const csrfToken = '<?php echo htmlspecialchars($this->generateCsrfToken()); ?>';

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        slotMinTime: '08:00:00',
        slotMaxTime: '20:00:00',
        allDaySlot: false,
        editable: true,
        selectable: true,
        events: '/clinicdashboard/api_events',

        // Handle Drag & Drop to reschedule
        eventDrop: function(info) {
            if(info.event.extendedProps.type === 'block') {
                alert("Cannot drag blocked time currently.");
                info.revert();
                return;
            }

            if(confirm("Are you sure you want to reschedule this appointment?")) {
                const formData = new FormData();
                formData.append('event_id', info.event.id);
                formData.append('new_start', info.event.start.toISOString());
                formData.append('csrf_token', csrfToken);

                fetch('/clinicdashboard/api_update_event', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if(!data.success) {
                        alert('Failed to reschedule.');
                        info.revert();
                    }
                })
                .catch(() => {
                    alert('Server error.');
                    info.revert();
                });
            } else {
                info.revert();
            }
        },

        // Handle Click to block time
        select: function(info) {
            const reason = prompt("Enter reason to block this time (e.g. Lunch, Surgery):");
            if (reason) {
                const formData = new FormData();
                formData.append('start', info.startStr);
                formData.append('end', info.endStr);
                formData.append('reason', reason);
                formData.append('csrf_token', csrfToken);

                fetch('/clinicdashboard/api_add_block', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        calendar.refetchEvents();
                    } else {
                        alert('Failed to block time.');
                    }
                });
            }
            calendar.unselect();
        },

        eventClick: function(info) {
            if(info.event.extendedProps.type === 'appointment') {
                // If we click an appointment, could redirect to attend view
                const appId = info.event.id.replace('app_', '');
                if(confirm(`Attend appointment with ${info.event.title}?`)) {
                    window.location.href = `/clinicdashboard/attend/${appId}`;
                }
            }
        }
    });

    calendar.render();
});
</script>

<style>
/* Fullcalendar dark mode overrides */
.dark .fc-theme-standard td, .dark .fc-theme-standard th, .dark .fc-theme-standard .fc-scrollgrid {
    border-color: #374151 !important;
}
.dark .fc-col-header-cell-cushion, .dark .fc-timegrid-slot-label-cushion {
    color: #e5e7eb;
}
.fc-event {
    cursor: pointer;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
}
</style>

<?php require APP_ROOT . '/app/views/inc/tail.php'; ?>
