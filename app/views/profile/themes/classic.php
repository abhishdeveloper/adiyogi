<?php require APP_ROOT . '/app/views/inc/head.php'; ?>

<!-- Classic Theme: Serif typography, muted colors, traditional layout -->
<div class="min-h-screen bg-[#f4f1ea] dark:bg-[#1a1814] transition-colors duration-300 py-16 font-serif">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-white dark:bg-[#24211c] border-2 border-[#e6e2d8] dark:border-[#38332b] shadow-2xl animate-on-scroll p-8 md:p-12">

            <div class="md:flex md:items-start md:space-x-12">
                <!-- Profile Image -->
                <div class="flex-shrink-0 mb-8 md:mb-0 text-center md:text-left">
                    <?php if($data['clinic']->profile_image): ?>
                        <img src="/assets/uploads/profiles/<?php echo htmlspecialchars($data['clinic']->profile_image); ?>" alt="Profile" class="w-48 h-48 rounded-sm object-cover border-4 border-[#e6e2d8] dark:border-[#38332b] mx-auto md:mx-0 p-1 bg-white dark:bg-black">
                    <?php else: ?>
                        <div class="w-48 h-48 rounded-sm border-4 border-[#e6e2d8] dark:border-[#38332b] bg-[#f8f6f0] dark:bg-[#1a1814] flex items-center justify-center mx-auto md:mx-0 p-1">
                            <i class="fa-solid fa-user-doctor text-[#c9c3b1] dark:text-[#4a443a] text-6xl"></i>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Header Info -->
                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-4xl md:text-5xl font-bold text-[#2d2821] dark:text-[#e8e4db] mb-2"><?php echo htmlspecialchars($data['clinic']->clinic_name); ?></h1>
                    <p class="text-xl text-[#7a6f5d] dark:text-[#a39a88] italic mb-4"><?php echo htmlspecialchars($data['clinic']->specialty); ?></p>
                    <p class="text-md text-[#5c5446] dark:text-[#c2b9a7] font-sans mb-6"><?php echo htmlspecialchars($data['clinic']->degrees); ?></p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start font-sans">
                        <a href="/patient/book/<?php echo htmlspecialchars($data['clinic']->id); ?>" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-sm text-white bg-[#5c5446] hover:bg-[#4a443a] dark:bg-[#8f836c] dark:hover:bg-[#7a6f5d] transition-colors">
                            Request Appointment
                        </a>
                        <a href="tel:<?php echo htmlspecialchars($data['clinic']->phone); ?>" class="inline-flex items-center justify-center px-6 py-3 border border-[#c9c3b1] dark:border-[#5c5446] text-base font-medium rounded-sm text-[#5c5446] dark:text-[#e8e4db] bg-transparent hover:bg-[#f8f6f0] dark:hover:bg-[#2d2821] transition-colors">
                            Contact Office
                        </a>
                    </div>
                </div>
            </div>

            <div class="my-12 border-t-2 border-[#e6e2d8] dark:border-[#38332b]"></div>

            <!-- Detailed Content -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 font-sans">

                <div>
                    <?php if(!empty($data['clinic']->bio)): ?>
                    <div class="mb-8">
                        <h3 class="text-lg font-serif font-bold text-[#2d2821] dark:text-[#e8e4db] mb-3">Practice Overview</h3>
                        <p class="text-[#5c5446] dark:text-[#a39a88] leading-relaxed whitespace-pre-line"><?php echo htmlspecialchars($data['clinic']->bio); ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if(!empty($data['clinic']->achievements)): ?>
                    <div>
                        <h3 class="text-lg font-serif font-bold text-[#2d2821] dark:text-[#e8e4db] mb-3">Honors & Awards</h3>
                        <p class="text-[#5c5446] dark:text-[#a39a88] leading-relaxed"><?php echo htmlspecialchars($data['clinic']->achievements); ?></p>
                    </div>
                    <?php endif; ?>
                </div>

                <div>
                    <div class="bg-[#f8f6f0] dark:bg-[#1f1c17] p-6 rounded-sm border border-[#e6e2d8] dark:border-[#38332b]">
                        <h3 class="text-lg font-serif font-bold text-[#2d2821] dark:text-[#e8e4db] mb-4">Location Details</h3>

                        <div class="flex items-start text-[#5c5446] dark:text-[#a39a88] mb-4">
                            <i class="fa-solid fa-map-location-dot mt-1 mr-3 text-[#8f836c] w-5"></i>
                            <span><?php echo htmlspecialchars($data['clinic']->address); ?><br>
                            <?php echo htmlspecialchars($data['clinic']->city . ', ' . $data['clinic']->state . ' ' . $data['clinic']->zip); ?></span>
                        </div>

                        <div class="flex items-center text-[#5c5446] dark:text-[#a39a88] mb-4">
                            <i class="fa-solid fa-phone mr-3 text-[#8f836c] w-5"></i>
                            <span><?php echo htmlspecialchars($data['clinic']->phone); ?></span>
                        </div>

                        <div class="flex items-center text-[#5c5446] dark:text-[#a39a88]">
                            <i class="fa-solid fa-id-card mr-3 text-[#8f836c] w-5"></i>
                            <span>Lic. #<?php echo htmlspecialchars($data['clinic']->license_number); ?></span>
                        </div>
                    </div>

                    <!-- Social Links Grid -->
                    <?php if(array_filter($data['socials'])): ?>
                    <div class="mt-8 flex gap-4">
                        <?php if(!empty($data['socials']['instagram'])): ?>
                            <a href="<?php echo htmlspecialchars($data['socials']['instagram']); ?>" target="_blank" class="w-10 h-10 rounded-sm bg-[#e6e2d8] dark:bg-[#38332b] flex items-center justify-center text-[#5c5446] dark:text-[#a39a88] hover:bg-[#d4cebd] transition-colors">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                        <?php endif; ?>
                        <?php if(!empty($data['socials']['facebook'])): ?>
                            <a href="<?php echo htmlspecialchars($data['socials']['facebook']); ?>" target="_blank" class="w-10 h-10 rounded-sm bg-[#e6e2d8] dark:bg-[#38332b] flex items-center justify-center text-[#5c5446] dark:text-[#a39a88] hover:bg-[#d4cebd] transition-colors">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                        <?php endif; ?>
                        <?php if(!empty($data['socials']['linkedin'])): ?>
                            <a href="<?php echo htmlspecialchars($data['socials']['linkedin']); ?>" target="_blank" class="w-10 h-10 rounded-sm bg-[#e6e2d8] dark:bg-[#38332b] flex items-center justify-center text-[#5c5446] dark:text-[#a39a88] hover:bg-[#d4cebd] transition-colors">
                                <i class="fa-brands fa-linkedin-in"></i>
                            </a>
                        <?php endif; ?>
                        <?php if(!empty($data['socials']['website'])): ?>
                            <a href="<?php echo htmlspecialchars($data['socials']['website']); ?>" target="_blank" class="w-10 h-10 rounded-sm bg-[#e6e2d8] dark:bg-[#38332b] flex items-center justify-center text-[#5c5446] dark:text-[#a39a88] hover:bg-[#d4cebd] transition-colors">
                                <i class="fa-solid fa-globe"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>

    </div>
</div>

<?php require APP_ROOT . '/app/views/inc/tail.php'; ?>
