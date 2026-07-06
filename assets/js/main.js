// Theme Toggle Logic
var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

// Change the icons inside the button based on previous settings
if (document.documentElement.classList.contains('dark')) {
    themeToggleLightIcon.classList.remove('hidden');
} else {
    themeToggleDarkIcon.classList.remove('hidden');
}

var themeToggleBtn = document.getElementById('theme-toggle');

if(themeToggleBtn) {
    themeToggleBtn.addEventListener('click', function() {

        // toggle icons inside button
        themeToggleDarkIcon.classList.toggle('hidden');
        themeToggleLightIcon.classList.toggle('hidden');

        // if set via local storage previously
        if (localStorage.getItem('color-theme')) {
            if (localStorage.getItem('color-theme') === 'light') {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            }

        // if NOT set via local storage previously
        } else {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            }
        }
    });
}

// Simple Intersection Observer for scroll animations
document.addEventListener('DOMContentLoaded', () => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('opacity-100', 'translate-y-0');
                entry.target.classList.remove('opacity-0', 'translate-y-8');
            }
        });
    }, {
        threshold: 0.1
    });

    document.querySelectorAll('.animate-on-scroll').forEach((el) => {
        el.classList.add('transition-all', 'duration-700', 'ease-out', 'opacity-0', 'translate-y-8');
        observer.observe(el);
    });
});

// Page Loader / Splash Screen Logic
window.addEventListener('load', function() {
    const loader = document.getElementById('page-loader');
    if (loader) {
        // Add a slight delay for visual effect
        setTimeout(() => {
            loader.classList.add('opacity-0');
            setTimeout(() => {
                loader.classList.add('hidden');
                loader.style.display = 'none';
            }, 500); // Wait for transition to finish
        }, 500);
    }
});

// Show loader when navigating to new pages (except anchor links or new tabs)
document.addEventListener('click', function(e) {
    const target = e.target.closest('a');
    if (target && target.href) {
        const url = new URL(target.href);
        // If it's internal, not an anchor link, and not a _blank target
        if (
            url.origin === window.location.origin &&
            !target.href.includes('#') &&
            target.getAttribute('target') !== '_blank' &&
            !target.hasAttribute('download')
        ) {
            const loader = document.getElementById('page-loader');
            if (loader) {
                loader.style.display = 'flex';
                loader.classList.remove('hidden');
                // Force reflow
                void loader.offsetWidth;
                loader.classList.remove('opacity-0');
            }
        }
    }
});
