<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin Panel' ?> - News Gate</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-blue-600">News Gate Admin</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700">
                        Halo, <strong><?php echo htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username']); ?></strong>
                        <?php if ($_SESSION['id_role'] == 1): ?>
                            <span class="ml-2 px-2 py-1 bg-red-100 text-red-700 text-xs rounded-full">Admin</span>
                        <?php elseif ($_SESSION['id_role'] == 2): ?>
                            <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">Editor</span>
                        <?php endif; ?>
                    </span>
                    <a href="<?php echo BASE_URL; ?>?route=admin/logout" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-200">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex">
