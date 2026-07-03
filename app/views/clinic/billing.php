<?php require APP_ROOT . '/app/views/inc/head.php'; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300 pb-safe">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between mb-6">
            <div class="flex-1 min-w-0">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-white sm:text-3xl sm:truncate">
                    Billing & Subscriptions
                </h2>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="/clinicdashboard/index" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    Back to Dashboard
                </a>
            </div>
        </div>

        <?php if(!empty($data['success_msg'])): ?>
            <div class="rounded-md bg-green-50 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0"><i class="fa-solid fa-circle-check text-green-400"></i></div>
                    <div class="ml-3"><p class="text-sm font-medium text-green-800"><?php echo htmlspecialchars($data['success_msg']); ?></p></div>
                </div>
            </div>
        <?php endif; ?>
        <?php if(!empty($data['error_msg'])): ?>
            <div class="rounded-md bg-red-50 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0"><i class="fa-solid fa-triangle-exclamation text-red-400"></i></div>
                    <div class="ml-3"><p class="text-sm font-medium text-red-800"><?php echo htmlspecialchars($data['error_msg']); ?></p></div>
                </div>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">

            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg border border-gray-200 dark:border-gray-700 p-6 text-center">
                    <?php if($data['clinic']->is_premium): ?>
                        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-crown text-yellow-500 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Premium Plan Active</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 mb-6">You have access to all premium features.</p>
                        <button disabled class="w-full inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-400 cursor-not-allowed">
                            Current Plan
                        </button>
                    <?php else: ?>
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid fa-user-doctor text-gray-500 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Basic Plan</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 mb-6">Upgrade to Premium to unlock custom colors and live chat.</p>
                        <form action="/clinicdashboard/billing" method="POST">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($this->generateCsrfToken()); ?>">
                            <input type="hidden" name="action" value="subscribe">
                            <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-secondary focus:outline-none transition-colors">
                                Upgrade to Premium (₹999/mo)
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Invoice History</h3>
                    </div>

                    <?php if(empty($data['invoices'])): ?>
                        <div class="p-6 text-center text-gray-500 dark:text-gray-400 text-sm">
                            No invoices found.
                        </div>
                    <?php else: ?>
                        <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                            <?php foreach($data['invoices'] as $invoice): ?>
                                <li class="px-4 py-4 sm:px-6 flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-bold text-primary"><?php echo htmlspecialchars($invoice->invoice_number); ?></p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            <?php echo date('M j, Y', strtotime($invoice->created_at)); ?> &middot;
                                            <span class="uppercase font-semibold"><?php echo htmlspecialchars($invoice->type); ?></span>
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">₹<?php echo number_format($invoice->amount, 2); ?></span>
                                        <span class="px-2 py-1 text-[10px] font-semibold rounded-full bg-green-100 text-green-800 uppercase tracking-wide">Paid</span>
                                        <button onclick="downloadInvoice('<?php echo htmlspecialchars($invoice->invoice_number); ?>', '<?php echo htmlspecialchars($invoice->type); ?>', '<?php echo $invoice->amount; ?>', '<?php echo date('M j, Y', strtotime($invoice->created_at)); ?>')" class="text-gray-400 hover:text-primary transition-colors" title="Download PDF">
                                            <i class="fa-solid fa-file-pdf text-xl"></i>
                                        </button>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<div id="invoice-template" class="hidden">
    <div style="padding: 40px; background: white; color: black; font-family: sans-serif;">
        <div style="border-bottom: 2px solid #0ea5e9; padding-bottom: 20px; margin-bottom: 20px; display: flex; justify-content: space-between;">
            <div>
                <h1 style="color: #0ea5e9; margin: 0; font-size: 24px;">MedClinicPro</h1>
                <p style="margin: 5px 0 0 0; color: #666;">Platform Subscription Receipt</p>
            </div>
            <div style="text-align: right;">
                <h2 style="margin: 0; color: #333;">INVOICE</h2>
                <p id="inv-num" style="margin: 5px 0 0 0; font-weight: bold;"></p>
            </div>
        </div>

        <div style="display: flex; justify-content: space-between; margin-bottom: 40px;">
            <div>
                <p style="color: #666; margin: 0 0 5px 0; font-size: 12px; text-transform: uppercase;">Billed To:</p>
                <p style="margin: 0; font-weight: bold;"><?php echo htmlspecialchars($data['clinic']->clinic_name); ?></p>
                <p style="margin: 5px 0 0 0; font-size: 14px;"><?php echo htmlspecialchars($data['clinic']->address); ?></p>
            </div>
            <div style="text-align: right;">
                <p style="color: #666; margin: 0 0 5px 0; font-size: 12px; text-transform: uppercase;">Date:</p>
                <p id="inv-date" style="margin: 0; font-weight: bold;"></p>
            </div>
        </div>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 40px;">
            <thead>
                <tr style="background-color: #f8fafc;">
                    <th style="padding: 12px; text-align: left; border-bottom: 1px solid #e2e8f0;">Description</th>
                    <th style="padding: 12px; text-align: right; border-bottom: 1px solid #e2e8f0;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td id="inv-desc" style="padding: 12px; border-bottom: 1px solid #e2e8f0; text-transform: capitalize;"></td>
                    <td id="inv-amt" style="padding: 12px; text-align: right; border-bottom: 1px solid #e2e8f0; font-weight: bold;"></td>
                </tr>
            </tbody>
        </table>

        <div style="text-align: right;">
            <p style="margin: 0; font-size: 14px; color: #666;">Total Paid</p>
            <p id="inv-total" style="margin: 5px 0 0 0; font-size: 24px; font-weight: bold; color: #0ea5e9;"></p>
        </div>
    </div>
</div>

<script>
function downloadInvoice(num, type, amt, dateStr) {
    document.getElementById('inv-num').innerText = num;
    document.getElementById('inv-date').innerText = dateStr;
    document.getElementById('inv-desc').innerText = "MedClinicPro " + type + " Fee";
    document.getElementById('inv-amt').innerText = "Rs. " + parseFloat(amt).toFixed(2);
    document.getElementById('inv-total').innerText = "Rs. " + parseFloat(amt).toFixed(2);

    const element = document.getElementById('invoice-template');
    element.classList.remove('hidden');

    const opt = {
        margin:       0,
        filename:     num + '.pdf',
        image:        { type: 'jpeg', quality: 0.98 },
        html2canvas:  { scale: 2 },
        jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
    };

    html2pdf().set(opt).from(element).save().then(() => {
        element.classList.add('hidden');
    });
}
</script>

<?php require APP_ROOT . '/app/views/inc/tail.php'; ?>
