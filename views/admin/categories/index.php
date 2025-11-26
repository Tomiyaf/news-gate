<?php 
$pageTitle = 'Kelola Kategori';
$currentPage = 'categories';
include __DIR__ . '/../layouts/header.php';
include __DIR__ . '/../layouts/sidebar.php';
?>

            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Kelola Kategori</h2>
                    <p class="text-gray-600 mt-1">Manage categories and sub-categories</p>
                </div>
                <a href="<?= BASE_URL ?>?route=admin/categories/create" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition flex items-center space-x-2">
                    <i class="fas fa-plus"></i>
                    <span>Tambah Kategori</span>
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

            <!-- Categories Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parent</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sub Kategori</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (count($tree) > 0): ?>
                            <?php foreach ($tree as $category): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-folder text-blue-600 mr-2"></i>
                                        <span class="font-semibold text-gray-900"><?= htmlspecialchars($category['name']) ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    <?= htmlspecialchars($category['description'] ?? '-') ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">-</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                                        <?= count($category['children']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="<?= BASE_URL ?>?route=admin/categories/edit&id=<?= $category['id_category'] ?>" class="text-blue-600 hover:text-blue-800 mr-3">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>?route=admin/categories/delete&id=<?= $category['id_category'] ?>" 
                                       onclick="return confirm('Yakin ingin menghapus kategori ini?')" 
                                       class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            
                            <!-- Sub Categories -->
                            <?php if (count($category['children']) > 0): ?>
                                <?php foreach ($category['children'] as $child): ?>
                                <tr class="hover:bg-gray-50 bg-gray-50">
                                    <td class="px-6 py-4 pl-12">
                                        <div class="flex items-center">
                                            <i class="fas fa-arrow-right text-gray-400 mr-2"></i>
                                            <span class="text-gray-700"><?= htmlspecialchars($child['name']) ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <?= htmlspecialchars($child['description'] ?? '-') ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <span class="text-blue-600"><?= htmlspecialchars($category['name']) ?></span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">-</td>
                                    <td class="px-6 py-4 text-sm">
                                        <a href="<?= BASE_URL ?>?route=admin/categories/edit&id=<?= $child['id_category'] ?>" class="text-blue-600 hover:text-blue-800 mr-3">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= BASE_URL ?>?route=admin/categories/delete&id=<?= $child['id_category'] ?>" 
                                           onclick="return confirm('Yakin ingin menghapus sub kategori ini?')" 
                                           class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-folder-open text-4xl mb-3 text-gray-300"></i>
                                <p>Belum ada kategori. Silakan tambah kategori baru.</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
