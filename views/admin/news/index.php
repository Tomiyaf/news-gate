<?php 
$pageTitle = 'Kelola Berita';
$currentPage = 'news';
include __DIR__ . '/../layouts/header.php';
include __DIR__ . '/../layouts/sidebar.php';
?>

            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Kelola Berita</h2>
                    <p class="text-gray-600 mt-1">Manage news articles</p>
                </div>
                <a href="<?= BASE_URL ?>?route=admin/news/create" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Tambah Berita</span>
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
                    <input type="hidden" name="route" value="admin/news">
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <input type="text" 
                                   name="search" 
                                   value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                                   placeholder="Judul atau konten berita..."
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <!-- Status Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Semua Status</option>
                                <option value="draft" <?= ($_GET['status'] ?? '') == 'draft' ? 'selected' : '' ?>>Draft</option>
                                <option value="published" <?= ($_GET['status'] ?? '') == 'published' ? 'selected' : '' ?>>Published</option>
                                <option value="archived" <?= ($_GET['status'] ?? '') == 'archived' ? 'selected' : '' ?>>Archived</option>
                            </select>
                        </div>
                        
                        <!-- Sort -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                            <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="created_at" <?= ($_GET['sort'] ?? 'created_at') == 'created_at' ? 'selected' : '' ?>>Tanggal Dibuat</option>
                                <option value="updated_at" <?= ($_GET['sort'] ?? '') == 'updated_at' ? 'selected' : '' ?>>Terakhir Update</option>
                                <option value="title" <?= ($_GET['sort'] ?? '') == 'title' ? 'selected' : '' ?>>Judul</option>
                                <option value="views" <?= ($_GET['sort'] ?? '') == 'views' ? 'selected' : '' ?>>Views</option>
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
                        <a href="<?= BASE_URL ?>?route=admin/news" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- News Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if (count($newsItems) > 0): ?>
                    <?php foreach ($newsItems as $item): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                        <!-- Thumbnail -->
                        <?php if (!empty($item['thumbnail_url'])): ?>
                        <img src="/news-gate/public/<?= htmlspecialchars($item['thumbnail_url']) ?>" 
                             alt="<?= htmlspecialchars($item['title']) ?>"
                             class="w-full h-48 object-cover">
                        <?php else: ?>
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Content -->
                        <div class="p-4">
                            <!-- Status Badge -->
                            <div class="mb-2">
                                <?php if ($item['status'] == 'published'): ?>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Published</span>
                                <?php elseif ($item['status'] == 'draft'): ?>
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Draft</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded-full">Archived</span>
                                <?php endif; ?>
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full ml-1">
                                    <?= htmlspecialchars($item['category_name'] ?? '-') ?>
                                </span>
                            </div>
                            
                            <!-- Title -->
                            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                                <?= htmlspecialchars($item['title']) ?>
                            </h3>
                            
                            <!-- Content Preview -->
                            <p class="text-sm text-gray-600 mb-3 line-clamp-3">
                                <?= htmlspecialchars(substr(strip_tags($item['content']), 0, 150)) ?><?= strlen(strip_tags($item['content'])) > 150 ? '...' : '' ?>
                            </p>
                            
                            <!-- Meta -->
                            <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                                <span>
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <?= htmlspecialchars($item['full_name'] ?? $item['username']) ?>
                                </span>
                                <span>
                                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <?= $item['views'] ?>
                                </span>
                            </div>
                            
                            <div class="text-xs text-gray-400 mb-4">
                                <?= date('d M Y H:i', strtotime($item['created_at'])) ?>
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex gap-2">
                                <a href="<?= BASE_URL ?>?route=admin/news/edit&id=<?= $item['id_news'] ?>" 
                                   class="flex-1 bg-blue-600 text-white text-center py-2 rounded hover:bg-blue-700 transition text-sm">
                                    Edit
                                </a>
                                <a href="<?= BASE_URL ?>?route=admin/news/delete&id=<?= $item['id_news'] ?>" 
                                   onclick="return confirm('Yakin ingin menghapus berita ini?')" 
                                   class="flex-1 bg-red-600 text-white text-center py-2 rounded hover:bg-red-700 transition text-sm">
                                    Hapus
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                <div class="col-span-full text-center py-12">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                    <p class="text-gray-500">Belum ada berita. Silakan tambah berita baru.</p>
                </div>
                <?php endif; ?>
            </div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
