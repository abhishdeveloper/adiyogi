<?php require APP_ROOT . '/app/views/inc/head.php'; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                    Consultation Details
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    <i class="fa-regular fa-clock mr-1"></i> <?php echo date('F j, Y - g:i a', strtotime($data['appointment']->appointment_datetime)); ?>
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="/patientdashboard/index" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    Back to Dashboard
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Prescription View -->
            <div class="lg:col-span-2">
                <?php if(!$data['prescription']): ?>
                    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg border border-gray-200 dark:border-gray-700 p-12 text-center h-full flex flex-col items-center justify-center">
                        <i class="fa-solid fa-file-prescription text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">The doctor has not written the prescription yet.</h3>
                        <p class="text-gray-500 dark:text-gray-400 mt-2">Check back later or chat with the clinic.</p>
                    </div>
                <?php else: ?>
                    <div class="bg-white shadow-lg border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                            <h3 class="text-lg font-medium text-gray-900"><i class="fa-solid fa-file-prescription text-primary mr-2"></i> Your Prescription</h3>
                            <button onclick="downloadPDF()" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-white bg-primary hover:bg-secondary transition-colors">
                                <i class="fa-solid fa-download mr-1"></i> Download PDF
                            </button>
                        </div>

                        <!-- Printable Area -->
                        <div id="prescription-content" class="p-8 bg-white relative">
                            <div class="border-b-2 border-gray-100 pb-4 mb-4">
                                <h1 class="text-2xl font-bold text-gray-900"><?php echo htmlspecialchars($data['prescription']->clinic_name); ?></h1>
                                <p class="text-gray-600 text-sm"><?php echo htmlspecialchars($data['prescription']->clinic_address); ?></p>
                                <p class="text-gray-600 text-sm"><i class="fa-solid fa-phone mr-1"></i> <?php echo htmlspecialchars($data['prescription']->clinic_phone); ?></p>
                            </div>

                            <div class="mb-6 flex justify-between">
                                <div><span class="text-gray-500 text-xs font-bold uppercase">Patient:</span> <br> <?php echo htmlspecialchars($data['prescription']->patient_first . ' ' . $data['prescription']->patient_last); ?></div>
                                <div class="text-right"><span class="text-gray-500 text-xs font-bold uppercase">Date:</span> <br> <?php echo date('d M Y', strtotime($data['prescription']->appointment_datetime)); ?></div>
                            </div>

                            <div class="mb-6">
                                <h4 class="font-bold text-gray-900 border-b border-gray-100 pb-1 mb-2">Diagnosis</h4>
                                <p class="text-gray-800 whitespace-pre-wrap text-sm"><?php echo htmlspecialchars($data['prescription']->diagnosis); ?></p>
                            </div>

                            <div class="mb-6">
                                <div class="text-3xl font-serif font-bold text-gray-900 mb-4">Rx</div>
                                <ul class="space-y-2 list-none pl-0 text-sm text-gray-800">
                                    <?php
                                        $lines = explode("\n", $data['prescription']->medicines);
                                        foreach($lines as $line) {
                                            if(trim($line)) {
                                                echo "<li class='border-b border-gray-50 pb-1'><i class='fa-solid fa-caret-right text-primary mr-2'></i>" . htmlspecialchars(trim($line)) . "</li>";
                                            }
                                        }
                                    ?>
                                </ul>
                            </div>

                            <?php if(!empty($data['prescription']->diet_instructions)): ?>
                                <div class="mb-6">
                                    <h4 class="font-bold text-gray-900 border-b border-gray-100 pb-1 mb-2">Diet & Instructions</h4>
                                    <p class="text-gray-800 whitespace-pre-wrap text-sm"><?php echo htmlspecialchars($data['prescription']->diet_instructions); ?></p>
                                </div>
                            <?php endif; ?>

                            <?php if(!empty($data['prescription']->notes)): ?>
                                <div class="mt-8 pt-4 border-t border-gray-100 text-sm text-gray-600 italic">
                                    <?php echo htmlspecialchars($data['prescription']->notes); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- AJAX Chat Area -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg border border-gray-200 dark:border-gray-700 h-[800px] flex flex-col">
                    <div class="px-4 py-4 sm:px-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 rounded-t-lg">
                        <h3 class="text-md leading-6 font-medium text-gray-900 dark:text-white">
                            <i class="fa-regular fa-comments text-primary mr-1"></i> Live Chat with Clinic
                        </h3>
                    </div>

                    <div id="chat-box" class="flex-1 p-4 overflow-y-auto bg-gray-50 dark:bg-gray-900/50 space-y-4" data-app-id="<?php echo $data['appointment']->id; ?>">
                        <div class="text-center text-xs text-gray-400 my-2">Chat started</div>
                    </div>

                    <div class="p-3 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-b-lg">
                        <form id="chat-form" onsubmit="sendChatMessage(event)" enctype="multipart/form-data">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($this->generateCsrfToken()); ?>">
                            <div class="flex items-center gap-2">
                                <label for="chat_file" class="cursor-pointer text-gray-400 hover:text-primary transition-colors p-2">
                                    <i class="fa-solid fa-paperclip text-lg"></i>
                                    <input type="file" id="chat_file" class="hidden" accept=".jpg,.jpeg,.png,.pdf">
                                </label>
                                <input type="text" id="chat_message" placeholder="Type a message..." class="flex-1 block w-full shadow-sm focus:ring-primary focus:border-primary sm:text-sm border-gray-300 dark:border-gray-600 rounded-full dark:bg-gray-700 dark:text-white px-4">
                                <button type="submit" class="inline-flex items-center p-2 border border-transparent rounded-full shadow-sm text-white bg-primary hover:bg-secondary focus:outline-none transition-colors">
                                    <i class="fa-solid fa-paper-plane px-1"></i>
                                </button>
                            </div>
                            <div id="file_preview" class="hidden mt-2 text-xs text-primary truncate px-2"></div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
// Chat AJAX Logic
let lastMsgId = 0;
const appId = document.getElementById('chat-box').dataset.appId;
const chatBox = document.getElementById('chat-box');
const fileInput = document.getElementById('chat_file');
const filePreview = document.getElementById('file_preview');

fileInput.addEventListener('change', function() {
    if(this.files.length > 0) {
        filePreview.textContent = "Attached: " + this.files[0].name;
        filePreview.classList.remove('hidden');
    } else {
        filePreview.classList.add('hidden');
    }
});

function fetchMessages() {
    fetch(`/chatapi/get_messages/${appId}?last_id=${lastMsgId}`)
        .then(response => response.json())
        .then(data => {
            if(data.messages && data.messages.length > 0) {
                data.messages.forEach(msg => {
                    lastMsgId = msg.id;
                    appendMessage(msg);
                });
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        });
}

function appendMessage(msg) {
    const div = document.createElement('div');
    div.className = `flex flex-col ${msg.is_mine ? 'items-end' : 'items-start'}`;

    let fileHtml = '';
    if(msg.file_path) {
        const isImg = msg.file_path.match(/\.(jpeg|jpg|gif|png)$/) != null;
        if(isImg) {
            fileHtml = `<a href="/assets/uploads/chat/${msg.file_path}" target="_blank"><img src="/assets/uploads/chat/${msg.file_path}" class="w-48 rounded mt-2 cursor-pointer border border-gray-200"></a>`;
        } else {
            fileHtml = `<a href="/assets/uploads/chat/${msg.file_path}" target="_blank" class="flex items-center text-sm mt-2 text-white hover:underline bg-gray-900/20 p-2 rounded"><i class="fa-solid fa-file-pdf mr-2"></i>View PDF</a>`;
        }
    }

    div.innerHTML = `
        <span class="text-xs text-gray-500 dark:text-gray-400 mb-1">${msg.sender_name} - ${msg.time}</span>
        <div class="px-4 py-2 rounded-2xl max-w-[80%] ${msg.is_mine ? 'bg-primary text-white rounded-br-none' : 'bg-gray-200 dark:bg-gray-700 dark:text-white rounded-bl-none'}">
            ${msg.message ? `<p class="whitespace-pre-wrap text-sm">${msg.message}</p>` : ''}
            ${fileHtml}
        </div>
    `;
    chatBox.appendChild(div);
}

function sendChatMessage(e) {
    e.preventDefault();
    const input = document.getElementById('chat_message');
    const file = fileInput.files[0];
    const csrfToken = document.querySelector('input[name="csrf_token"]').value;

    if(!input.value.trim() && !file) return;

    const formData = new FormData();
    formData.append('appointment_id', appId);
    formData.append('message', input.value.trim());
    formData.append('csrf_token', csrfToken);
    if(file) formData.append('file', file);

    input.value = '';
    fileInput.value = '';
    filePreview.classList.add('hidden');

    fetch('/chatapi/send_message', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            fetchMessages();
        } else if(data.error) {
            alert(data.error);
        }
    });
}

setInterval(fetchMessages, 3000);
fetchMessages();

// PDF Download
function downloadPDF() {
    const element = document.getElementById('prescription-content');
    const opt = {
        margin:       0.5,
        filename:     'Prescription.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2 },
        jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
    };
    html2pdf().set(opt).from(element).save();
}
</script>

<?php require APP_ROOT . '/app/views/inc/tail.php'; ?>
