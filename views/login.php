<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - News Gate</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="<?php echo BASE_URL; ?>" class="text-2xl font-bold text-blue-600">News Gate</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Login Form -->
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Login</h2>
                <p class="text-gray-600 mt-2">Masuk ke akun Anda</p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                    <?php 
                        echo htmlspecialchars($_SESSION['error']); 
                        unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo BASE_URL; ?>?route=login" method="POST" class="space-y-6">
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                        Username
                    </label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                        placeholder="Masukkan username"
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
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                        placeholder="Masukkan password"
                    >
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 rounded-lg transition duration-200"
                >
                    Login
                </button>
            </form>

            <div class="mt-6 text-center space-y-3">
                <p class="text-gray-600 text-sm">
                    Belum punya akun? 
                    <a href="<?php echo BASE_URL; ?>?route=register" class="text-blue-500 hover:text-blue-600 font-semibold">
                        Daftar di sini
                    </a>
                </p>
                <a href="<?php echo BASE_URL; ?>" class="block text-gray-500 hover:text-gray-600 text-sm">
                    ← Kembali ke Home
                </a>
            </div>
        </div>
    </div>
</body>
</html>
