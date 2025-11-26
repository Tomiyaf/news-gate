<?php 
$pageTitle = 'Edit User';
$currentPage = 'users';
include __DIR__ . '/../layouts/header.php';
include __DIR__ . '/../layouts/sidebar.php';
?>

            <!-- Header -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Edit User</h2>
                <p class="text-gray-600 mt-1">Update user information</p>
            </div>

            <!-- Error Messages -->
            <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
            <?php endif; ?>

            <!-- Form -->
            <div class="bg-white rounded-lg shadow-md p-8 max-w-2xl">
                <form action="<?= BASE_URL ?>?route=admin/users/update" method="POST">
                    <input type="hidden" name="id_user" value="<?= $user['id_user'] ?>">
                    
                    <!-- Username -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="username" 
                               name="username" 
                               required
                               value="<?= htmlspecialchars($user['username']) ?>"
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
                               value="<?= htmlspecialchars($user['email']) ?>"
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
                               value="<?= htmlspecialchars($user['full_name']) ?>"
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
                            <?php foreach ($roles as $role): ?>
                            <option value="<?= $role['id_role'] ?>" <?= $user['id_role'] == $role['id_role'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($role['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <hr class="my-6">

                    <!-- Password Section -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <p class="text-sm text-yellow-800 mb-4">
                            <strong>Update Password:</strong> Kosongkan jika tidak ingin mengubah password
                        </p>
                        
                        <!-- New Password -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                                Password Baru
                            </label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   minlength="6"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                   placeholder="Masukkan password baru (min. 6 karakter)">
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-0">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="confirm_password">
                                Konfirmasi Password Baru
                            </label>
                            <input type="password" 
                                   id="confirm_password" 
                                   name="confirm_password" 
                                   minlength="6"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                   placeholder="Konfirmasi password baru">
                        </div>
                    </div>

                    <!-- Note about Avatar -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <p class="text-sm text-blue-800">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <strong>Catatan:</strong> Avatar hanya bisa diubah oleh user yang bersangkutan melalui halaman profil mereka.
                        </p>
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
                            Update User
                        </button>
                    </div>
                </form>
            </div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
