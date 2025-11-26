<?php 
$pageTitle = 'Tambah User';
$currentPage = 'users';
include __DIR__ . '/../layouts/header.php';
include __DIR__ . '/../layouts/sidebar.php';
?>

            <!-- Header -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Tambah User</h2>
                <p class="text-gray-600 mt-1">Create new user with specific role</p>
            </div>

            <!-- Error Messages -->
            <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
            <?php endif; ?>

            <!-- Form -->
            <div class="bg-white rounded-lg shadow-md p-8 max-w-2xl">
                <form action="<?= BASE_URL ?>?route=admin/users/store" method="POST">
                    <!-- Username -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="username" 
                               name="username" 
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               placeholder="Masukkan username">
                    </div>

                    <!-- Email -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               placeholder="Masukkan email">
                    </div>

                    <!-- Full Name -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="full_name">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="full_name" 
                               name="full_name" 
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               placeholder="Masukkan nama lengkap">
                    </div>

                    <!-- Role -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="id_role">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select id="id_role" 
                                name="id_role" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih Role --</option>
                            <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['id_role'] ?>"><?= htmlspecialchars($role['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Password -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               required
                               minlength="6"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               placeholder="Masukkan password (min. 6 karakter)">
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="confirm_password">
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" 
                               id="confirm_password" 
                               name="confirm_password" 
                               required
                               minlength="6"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               placeholder="Konfirmasi password">
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-between">
                        <a href="<?= BASE_URL ?>?route=admin/users" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Kembali
                        </a>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan User
                        </button>
                    </div>
                </form>
            </div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
