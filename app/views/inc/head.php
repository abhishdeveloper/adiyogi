<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? 'Medical Clinics App' ?></title>
    <meta name="description" content="<?= $data['description'] ?? 'Secure, scalable multi-clinic management system.' ?>">

    <!-- Tailwind CSS (via CDN for shared hosting without build tools) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#0ea5e9',
                        secondary: '#38bdf8',
                        dark: '#0f172a',
                    }
                }
            }
        }
    </script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">

    <!-- FontAwesome for Icons (Optional, using a popular CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Prevent FOUC for Dark Mode -->
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-dark dark:text-gray-100 transition-colors duration-300">

    <!-- Header / Navbar -->
    <?php require APP_ROOT . '/app/views/inc/header.php'; ?>

    <!-- Main Content -->
    <main class="min-h-screen">
