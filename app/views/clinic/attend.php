<?php require APP_ROOT . '/app/views/inc/head.php'; ?>

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                    Consultation: <?php echo htmlspecialchars($data['appointment']->first_name . ' ' . $data['appointment']->last_name); ?>
                </h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    <i class="fa-regular fa-clock mr-1"></i> <?php echo date('F j, Y - g:i a', strtotime($data['appointment']->appointment_datetime)); ?>
                </p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="/clinicdashboard/index" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    Back to Dashboard
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Prescription Form -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Write Prescription</h3>

                        <!-- Load Presaved Template Dropdown -->
                        <?php if(!empty($data['presaved']['templates'])): ?>
                            <select id="load_template" class="block w-48 pl-3 pr-10 py-1 text-sm border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-primary focus:border-primary rounded-md dark:bg-gray-700 dark:text-white" onchange="insertTemplate(this)">
                                <option value="">Load Template...</option>
                                <?php foreach($data['presaved']['templates'] as $t): ?>
                                    <option value="<?php echo htmlspecialchars($t->content); ?>"><?php echo htmlspecialchars($t->title); ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </div>

                    <div class="px-4 py-5 sm:p-6">
                        <form action="/clinicdashboard/attend/<?php echo $data['appointment']->id; ?>" method="POST" class="space-y-6">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($this->generateCsrfToken()); ?>">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Diagnosis / Findings</label>
                                <textarea name="diagnosis" id="presc_diagnosis" rows="2" class="mt-1 block w-full shadow-sm focus:ring-primary focus:border-primary sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white" required></textarea>
                            </div>

                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Medicines (Rx)</label>
                                    <!-- Insert Presaved Medicine -->
                                    <?php if(!empty($data['presaved']['medicines'])): ?>
                                        <select class="block w-48 pl-3 pr-10 py-1 text-xs border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-primary focus:border-primary rounded-md dark:bg-gray-700 dark:text-white" onchange="insertText('presc_medicines', this)">
                                            <option value="">+ Add Presaved Medicine</option>
                                            <?php foreach($data['presaved']['medicines'] as $m): ?>
                                                <option value="<?php echo htmlspecialchars($m->title . " - " . $m->content); ?>"><?php echo htmlspecialchars($m->title); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php endif; ?>
                                </div>
                                <textarea name="medicines" id="presc_medicines" rows="5" class="block w-full shadow-sm focus:ring-primary focus:border-primary sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white font-mono" required></textarea>
                            </div>

                            <div>
                                <div class="flex justify-between items-center mb-1">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Diet & Instructions</label>
                                    <!-- Insert Presaved Diet -->
                                    <?php if(!empty($data['presaved']['diets'])): ?>
                                        <select class="block w-48 pl-3 pr-10 py-1 text-xs border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-primary focus:border-primary rounded-md dark:bg-gray-700 dark:text-white" onchange="insertText('presc_diets', this)">
                                            <option value="">+ Add Presaved Diet</option>
                                            <?php foreach($data['presaved']['diets'] as $d): ?>
                                                <option value="<?php echo htmlspecialchars($d->title . ":\n" . $d->content); ?>"><?php echo htmlspecialchars($d->title); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php endif; ?>
                                </div>
                                <textarea name="diet_instructions" id="presc_diets" rows="3" class="block w-full shadow-sm focus:ring-primary focus:border-primary sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white"></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Additional Notes / Next Visit</label>
                                <textarea name="notes" rows="2" class="mt-1 block w-full shadow-sm focus:ring-primary focus:border-primary sm:text-sm border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white"></textarea>
                            </div>

                            <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                                <button type="submit" onclick="return confirm('Finish appointment and generate prescription? This action cannot be undone.');" class="w-full flex justify-center py-3 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-secondary focus:outline-none transition-colors">
                                    Save Prescription & Mark Completed
                                </button>
                                <p class="text-xs text-center text-gray-500 mt-2">The patient will be emailed automatically.</p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- AJAX Chat & File Sharing Area -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg border border-gray-200 dark:border-gray-700 h-[800px] flex flex-col">
                    <div class="px-4 py-4 sm:px-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 rounded-t-lg">
                        <h3 class="text-md leading-6 font-medium text-gray-900 dark:text-white">
                            <i class="fa-regular fa-comments text-primary mr-1"></i> Live Chat & Files
                        </h3>
                    </div>

                    <!-- Chat Messages Container -->
                    <div id="chat-box" class="flex-1 p-4 overflow-y-auto bg-gray-50 dark:bg-gray-900/50 space-y-4" data-app-id="<?php echo $data['appointment']->id; ?>">
                        <div class="text-center text-xs text-gray-400 my-2">Chat started</div>
                        <!-- Messages loaded via AJAX -->
                    </div>

                    <!-- Chat Input -->
                    <div class="p-3 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-b-lg">
                        <form id="chat-form" onsubmit="sendChatMessage(event)" enctype="multipart/form-data">
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
            fetchMessages(); // Fetch immediately after sending
        } else if(data.error) {
            alert(data.error);
        }
    });
}

// Poll every 3 seconds
setInterval(fetchMessages, 3000);
fetchMessages(); // Initial fetch

// JavaScript Helpers for Presaved Items
function insertTemplate(select) {
    if(!select.value) return;
    try {
        const tpl = JSON.parse(select.value);
        if(tpl.diagnosis) document.getElementById('presc_diagnosis').value = tpl.diagnosis;
        if(tpl.medicines) document.getElementById('presc_medicines').value = tpl.medicines;
        if(tpl.diet) document.getElementById('presc_diets').value = tpl.diet;
    } catch(e) {
        // If not JSON, just append to notes
        document.getElementById('presc_diagnosis').value += select.value;
    }
    select.value = "";
}

function insertText(targetId, select) {
    if(!select.value) return;
    const target = document.getElementById(targetId);
    if(target.value.length > 0) {
        target.value += "\n\n" + select.value;
    } else {
        target.value = select.value;
    }
    select.value = "";
}
</script>

<?php require APP_ROOT . '/app/views/inc/tail.php'; ?>
