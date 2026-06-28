<?php require APP_ROOT . '/app/views/inc/head.php'; ?>

<!-- Minimal Theme: Dark mode focused, typographic, borderless -->
<div class="min-h-screen bg-[#0a0a0a] text-gray-300 py-16 transition-colors duration-300">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="animate-on-scroll">

            <div class="flex flex-col items-center mb-12">
                <!-- Profile Image -->
                <?php if($data['clinic']->profile_image): ?>
                    <img src="/assets/uploads/profiles/<?php echo htmlspecialchars($data['clinic']->profile_image); ?>" alt="Profile" class="w-24 h-24 rounded-full object-cover mb-6 ring-2 ring-gray-800">
                <?php else: ?>
                    <div class="w-24 h-24 rounded-full bg-gray-900 flex items-center justify-center mb-6 ring-2 ring-gray-800">
                        <i class="fa-solid fa-user-doctor text-gray-500 text-3xl"></i>
                    </div>
                <?php endif; ?>

                <h1 class="text-4xl font-light text-white text-center tracking-tight"><?php echo htmlspecialchars($data['clinic']->clinic_name); ?></h1>
                <p class="mt-2 text-md text-gray-400 font-medium tracking-wide uppercase"><?php echo htmlspecialchars($data['clinic']->specialty); ?></p>
                <p class="text-sm text-gray-500 mt-1"><?php echo htmlspecialchars($data['clinic']->degrees); ?></p>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-4 mb-12">
                <a href="/patient/book/<?php echo htmlspecialchars($data['clinic']->id); ?>" class="flex items-center justify-center w-full py-4 px-6 bg-white text-black font-semibold rounded-lg hover:bg-gray-200 transition-colors duration-200">
                    Book Appointment
                </a>
                <a href="tel:<?php echo htmlspecialchars($data['clinic']->phone); ?>" class="flex items-center justify-center w-full py-4 px-6 bg-transparent border border-gray-700 text-white font-medium rounded-lg hover:bg-gray-900 transition-colors duration-200">
                    Call <?php echo htmlspecialchars($data['clinic']->phone); ?>
                </a>
            </div>

            <div class="space-y-8 text-center px-4">
                <?php if(!empty($data['clinic']->bio)): ?>
                <div>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-3">About</h3>
                    <p class="text-gray-300 text-sm leading-relaxed whitespace-pre-line"><?php echo htmlspecialchars($data['clinic']->bio); ?></p>
                </div>
                <?php endif; ?>

                <?php if(!empty($data['clinic']->achievements)): ?>
                <div>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-3">Achievements</h3>
                    <p class="text-gray-300 text-sm italic">"<?php echo htmlspecialchars($data['clinic']->achievements); ?>"</p>
                </div>
                <?php endif; ?>

                <div>
                    <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-3">Location</h3>
                    <p class="text-gray-300 text-sm"><?php echo htmlspecialchars($data['clinic']->address); ?><br><?php echo htmlspecialchars($data['clinic']->city . ', ' . $data['clinic']->state . ' ' . $data['clinic']->zip); ?></p>
                </div>
            </div>

            <!-- Social Links Grid -->
            <?php if(array_filter($data['socials'])): ?>
            <div class="mt-12 flex justify-center gap-8">
                <?php if(!empty($data['socials']['instagram'])): ?>
                    <a href="<?php echo htmlspecialchars($data['socials']['instagram']); ?>" target="_blank" class="text-gray-500 hover:text-white transition-colors">
                        <i class="fa-brands fa-instagram text-xl"></i>
                    </a>
                <?php endif; ?>
                <?php if(!empty($data['socials']['facebook'])): ?>
                    <a href="<?php echo htmlspecialchars($data['socials']['facebook']); ?>" target="_blank" class="text-gray-500 hover:text-white transition-colors">
                        <i class="fa-brands fa-facebook-f text-xl"></i>
                    </a>
                <?php endif; ?>
                <?php if(!empty($data['socials']['linkedin'])): ?>
                    <a href="<?php echo htmlspecialchars($data['socials']['linkedin']); ?>" target="_blank" class="text-gray-500 hover:text-white transition-colors">
                        <i class="fa-brands fa-linkedin-in text-xl"></i>
                    </a>
                <?php endif; ?>
                <?php if(!empty($data['socials']['website'])): ?>
                    <a href="<?php echo htmlspecialchars($data['socials']['website']); ?>" target="_blank" class="text-gray-500 hover:text-white transition-colors">
                        <i class="fa-solid fa-link text-xl"></i>
                    </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php require APP_ROOT . '/app/views/inc/tail.php'; ?>
