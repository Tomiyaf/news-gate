<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - News Gate</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-900 to-blue-700 min-h-screen">
    <!-- Login Form -->
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full bg-white rounded-lg shadow-2xl p-8">
            <!-- Logo/Icon -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-full mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900">Admin Panel</h2>
                <p class="text-gray-600 mt-2">News Gate Management System</p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                    <?php 
                        echo htmlspecialchars($_SESSION['error']); 
                        unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo BASE_URL; ?>?route=admin/login" method="POST" class="space-y-6">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                        Username
                    </label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                        placeholder="Masukkan username"
                        autofocus
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                        placeholder="Masukkan password"
                    >
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition duration-200 shadow-lg hover:shadow-xl"
                >
                    Login
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="<?php echo BASE_URL; ?>" class="text-blue-600 hover:text-blue-700 text-sm">
                    ← Kembali ke Beranda
                </a>
            </div>

            <!-- Footer Note -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-xs text-gray-500 text-center">
                    🔒 Halaman ini hanya untuk Admin dan Editor
                </p>
            </div>
        </div>
    </div>
</body>
</html>
