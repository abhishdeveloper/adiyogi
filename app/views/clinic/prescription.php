<?php require APP_ROOT . '/app/views/inc/head.php'; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-4 flex justify-between items-center">
            <a href="/clinicdashboard/index" class="text-primary hover:text-secondary text-sm font-medium">
                &larr; Back to Dashboard
            </a>
            <div class="flex space-x-2 border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-lg p-1">
                <button onclick="downloadPDF()" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                    <i class="fa-solid fa-download mr-1"></i> Download PDF
                </button>
                <a href="<?php echo $data['whatsapp_link']; ?>" target="_blank" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-green-500 hover:bg-green-600 transition-colors">
                    <i class="fa-brands fa-whatsapp mr-1"></i> Send via WhatsApp
                </a>
                <a href="<?php echo $data['telegram_link']; ?>" target="_blank" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-blue-500 hover:bg-blue-600 transition-colors">
                    <i class="fa-brands fa-telegram mr-1"></i> Send via Telegram
                </a>
            </div>
        </div>

        <!-- Printable Prescription Container -->
        <div id="prescription-content" class="bg-white shadow-lg p-10 border-t-8 border-primary relative overflow-hidden">
            <!-- Watermark -->
            <div class="absolute inset-0 flex items-center justify-center opacity-5 pointer-events-none">
                <i class="fa-solid fa-staff-snake text-[20rem]"></i>
            </div>

            <!-- Header -->
            <div class="flex justify-between items-start border-b-2 border-gray-100 pb-6 mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 m-0"><?php echo htmlspecialchars($data['prescription']->clinic_name); ?></h1>
                    <p class="text-gray-600 text-sm mt-1"><?php echo htmlspecialchars($data['prescription']->clinic_address); ?></p>
                    <p class="text-gray-600 text-sm"><i class="fa-solid fa-phone mr-1"></i> <?php echo htmlspecialchars($data['prescription']->clinic_phone); ?></p>
                </div>
                <div class="text-right">
                    <div class="text-4xl text-primary"><i class="fa-solid fa-user-doctor"></i></div>
                </div>
            </div>

            <!-- Patient Info -->
            <div class="bg-gray-50 rounded p-4 mb-8 flex justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Patient Name</p>
                    <p class="font-medium text-gray-900"><?php echo htmlspecialchars($data['prescription']->patient_first . ' ' . $data['prescription']->patient_last); ?></p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Date</p>
                    <p class="font-medium text-gray-900"><?php echo date('d M Y', strtotime($data['prescription']->appointment_datetime)); ?></p>
                </div>
            </div>

            <!-- Rx Body -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 min-h-[400px]">

                <!-- Left Sidebar (Vitals / Findings) -->
                <div class="col-span-1 border-r border-gray-100 pr-6">
                    <h3 class="font-bold text-gray-900 border-b border-gray-200 pb-2 mb-4">Diagnosis / Findings</h3>
                    <p class="text-sm text-gray-700 whitespace-pre-wrap"><?php echo htmlspecialchars($data['prescription']->diagnosis); ?></p>

                    <?php if(!empty($data['prescription']->diet_instructions)): ?>
                        <h3 class="font-bold text-gray-900 border-b border-gray-200 pb-2 mb-4 mt-8">Diet & Instructions</h3>
                        <p class="text-sm text-gray-700 whitespace-pre-wrap"><?php echo htmlspecialchars($data['prescription']->diet_instructions); ?></p>
                    <?php endif; ?>
                </div>

                <!-- Main Content (Medicines) -->
                <div class="col-span-2 pl-2">
                    <div class="text-4xl font-serif font-bold text-gray-900 mb-6">Rx</div>
                    <div class="prose prose-sm text-gray-800">
                        <?php
                            $lines = explode("\n", $data['prescription']->medicines);
                            echo "<ul class='space-y-4 list-none pl-0'>";
                            foreach($lines as $line) {
                                if(trim($line)) {
                                    echo "<li class='border-b border-gray-50 pb-2'><i class='fa-solid fa-capsules text-gray-400 mr-2'></i>" . htmlspecialchars(trim($line)) . "</li>";
                                }
                            }
                            echo "</ul>";
                        ?>
                    </div>

                    <?php if(!empty($data['prescription']->notes)): ?>
                        <div class="mt-12 bg-yellow-50 border border-yellow-100 p-4 rounded text-sm text-yellow-800">
                            <strong>Note:</strong> <?php echo htmlspecialchars($data['prescription']->notes); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-16 pt-8 border-t border-gray-100 text-center text-xs text-gray-400">
                <p>This is a digitally generated prescription.</p>
                <p>Powered by MedClinicPro</p>
            </div>
        </div>

    </div>
</div>

<script>
function downloadPDF() {
    const element = document.getElementById('prescription-content');
    const opt = {
        margin:       0,
        filename:     'Prescription_<?php echo date('Ymd', strtotime($data['prescription']->appointment_datetime)); ?>.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2 },
        jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
    };

    html2pdf().set(opt).from(element).save();
}
</script>

<?php require APP_ROOT . '/app/views/inc/tail.php'; ?>
