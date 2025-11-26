<?php 
$pageTitle = 'Kelola User';
$currentPage = 'users';
include __DIR__ . '/../layouts/header.php';
include __DIR__ . '/../layouts/sidebar.php';
?>

            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Kelola User</h2>
                    <p class="text-gray-600 mt-1">Manage users and their roles</p>
                </div>
                <a href="<?= BASE_URL ?>?route=admin/users/create" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah User</span>
                </a>
            </div>

            <!-- Success/Error Messages -->
            <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
            <?php endif; ?>

            <!-- Filter and Search -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <form method="GET" action="<?= BASE_URL ?>">
                    <input type="hidden" name="route" value="admin/users">
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <input type="text" 
                                   name="search" 
                                   value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                                   placeholder="Username, nama, atau email..."
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <!-- Role Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Filter Role</label>
                            <select name="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Role</option>
                                <?php foreach ($roles as $role): ?>
                                <option value="<?= $role['id_role'] ?>" <?= ($_GET['role'] ?? '') == $role['id_role'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($role['name']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Sort -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                            <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="username" <?= ($_GET['sort'] ?? 'username') == 'username' ? 'selected' : '' ?>>Username</option>
                                <option value="full_name" <?= ($_GET['sort'] ?? '') == 'full_name' ? 'selected' : '' ?>>Nama Lengkap</option>
                                <option value="email" <?= ($_GET['sort'] ?? '') == 'email' ? 'selected' : '' ?>>Email</option>
                                <option value="id_role" <?= ($_GET['sort'] ?? '') == 'id_role' ? 'selected' : '' ?>>Role</option>
                                <option value="created_at" <?= ($_GET['sort'] ?? '') == 'created_at' ? 'selected' : '' ?>>Tanggal Dibuat</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex gap-2 mt-4">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Filter
                        </button>
                        <a href="<?= BASE_URL ?>?route=admin/users" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">
                            Reset
                        </a>
                        <input type="hidden" name="order" value="<?= ($_GET['order'] ?? 'asc') == 'asc' ? 'desc' : 'asc' ?>">
                    </div>
                </form>
            </div>

            <!-- Users Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Lengkap</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Dibuat</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (count($users) > 0): ?>
                            <?php foreach ($users as $user): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-semibold mr-3">
                                            <?= strtoupper(substr($user['username'], 0, 1)) ?>
                                        </div>
                                        <span class="font-medium text-gray-900"><?= htmlspecialchars($user['username']) ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?= htmlspecialchars($user['full_name'] ?? '-') ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?= htmlspecialchars($user['email'] ?? '-') ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($user['id_role'] == 1): ?>
                                        <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Admin</span>
                                    <?php elseif ($user['id_role'] == 2): ?>
                                        <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">Editor</span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded-full">User</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?= date('d M Y', strtotime($user['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="<?= BASE_URL ?>?route=admin/users/edit&id=<?= $user['id_user'] ?>" class="text-blue-600 hover:text-blue-800 mr-3">
                                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <?php if ($user['id_user'] != $_SESSION['id_user']): ?>
                                    <a href="<?= BASE_URL ?>?route=admin/users/delete&id=<?= $user['id_user'] ?>" 
                                       onclick="return confirm('Yakin ingin menghapus user <?= htmlspecialchars($user['username']) ?>?')" 
                                       class="text-red-600 hover:text-red-800">
                                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <p>Tidak ada user ditemukan.</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
