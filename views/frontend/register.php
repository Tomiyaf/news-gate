<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - News Gate</title>
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

    <!-- Register Form -->
    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Daftar Akun</h2>
                <p class="text-gray-600 mt-2">Buat akun baru untuk membaca berita</p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                    <?php 
                        echo htmlspecialchars($_SESSION['error']); 
                        unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                    <?php 
                        echo htmlspecialchars($_SESSION['success']); 
                        unset($_SESSION['success']);
                    ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo BASE_URL; ?>?route=register" method="POST" class="space-y-4">
                <div>
                    <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="full_name" 
                        name="full_name" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                        placeholder="Masukkan nama lengkap"
                        value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>"
                    >
                </div>

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                        Username <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        required
                        minlength="4"
                        pattern="[a-zA-Z0-9_]+"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                        placeholder="Masukkan username (min 4 karakter)"
                        value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                    >
                    <p class="text-xs text-gray-500 mt-1">Hanya huruf, angka, dan underscore (_)</p>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                        placeholder="nama@email.com"
                        value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required
                        minlength="6"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                        placeholder="Minimal 6 karakter"
                    >
                </div>

                <div>
                    <label for="password_confirm" class="block text-sm font-medium text-gray-700 mb-2">
                        Konfirmasi Password <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="password" 
                        id="password_confirm" 
                        name="password_confirm" 
                        required
                        minlength="6"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                        placeholder="Ulangi password"
                    >
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-3 rounded-lg transition duration-200"
                >
                    Daftar
                </button>
            </form>

            <div class="mt-6 text-center space-y-3">
                <p class="text-gray-600 text-sm">
                    Sudah punya akun? 
                    <a href="<?php echo BASE_URL; ?>?route=login" class="text-blue-500 hover:text-blue-600 font-semibold">
                        Login di sini
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
