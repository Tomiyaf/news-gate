<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= !empty($selectedCategory) ? htmlspecialchars($selectedCategory['name']) . ' - ' : '' ?><?= !empty($search) ? 'Pencarian: ' . htmlspecialchars($search) . ' - ' : '' ?>Berita - News Gate</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Navbar Component -->
    <?php require_once __DIR__ . '/../components/navbar.php'; ?>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Header Section -->
        <div class="mb-8">
            <?php if (!empty($selectedCategory)): ?>
                <div class="mb-4">
                    <span class="text-sm text-gray-500">Kategori</span>
                    <h1 class="text-4xl font-bold text-gray-900 mt-1"><?= htmlspecialchars($selectedCategory['name']) ?></h1>
                </div>
            <?php elseif (!empty($search)): ?>
                <div class="mb-4">
                    <span class="text-sm text-gray-500">Hasil Pencarian</span>
                    <h1 class="text-4xl font-bold text-gray-900 mt-1">"<?= htmlspecialchars($search) ?>"</h1>
                    <p class="text-gray-600 mt-2">Ditemukan <?= number_format($totalNews) ?> berita</p>
                </div>
            <?php else: ?>
                <h1 class="text-4xl font-bold text-gray-900">Semua Berita</h1>
            <?php endif; ?>
        </div>

        <!-- Filters Bar -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                
                <!-- Category Filter -->
                <div class="flex-1">
                    <label class="text-sm font-semibold text-gray-700 mb-2 block">Filter Kategori</label>
                    <select id="categoryFilter" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Kategori</option>
                        <?php
                        $currentParent = '';
                        foreach ($categories as $cat):
                            // Show parent category as optgroup header
                            if (!empty($cat['id_parent'])):
                                // Get parent name
                                $parentCat = array_filter($categories, function($c) use ($cat) {
                                    return $c['id_category'] == $cat['id_parent'];
                                });
                                $parentName = !empty($parentCat) ? reset($parentCat)['name'] : '';
                                
                                if ($currentParent != $parentName):
                                    if ($currentParent != '') echo '</optgroup>';
                                    echo '<optgroup label="' . htmlspecialchars($parentName) . '">';
                                    $currentParent = $parentName;
                                endif;
                            endif;
                        ?>
                            <option value="<?= $cat['id_category'] ?>" <?= ($categoryId == $cat['id_category']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                        <?php if ($currentParent != ''): ?>
                            </optgroup>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Sort Filter -->
                <div class="lg:w-64">
                    <label class="text-sm font-semibold text-gray-700 mb-2 block">Urutkan</label>
                    <select id="sortFilter" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="latest" <?= ($sortBy == 'latest') ? 'selected' : '' ?>>Terbaru</option>
                        <option value="popular" <?= ($sortBy == 'popular') ? 'selected' : '' ?>>Terpopuler</option>
                        <option value="trending" <?= ($sortBy == 'trending') ? 'selected' : '' ?>>Trending</option>
                    </select>
                </div>

                <!-- Reset Button -->
                <?php if (!empty($categoryId) || !empty($search) || $sortBy != 'latest'): ?>
                <div class="lg:self-end">
                    <a href="<?= BASE_URL ?>?route=news" 
                       class="inline-flex items-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset Filter
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- News Grid -->
        <?php if (!empty($newsItems)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <?php foreach ($newsItems as $news): ?>
            <article class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-lg transition-all duration-300 group border border-gray-100">
                <!-- Thumbnail -->
                <div class="relative h-52 overflow-hidden bg-gray-200">
                    <?php if (!empty($news['thumbnail_url'])): ?>
                        <img src="/news-gate/public/<?= htmlspecialchars($news['thumbnail_url']) ?>" 
                             alt="<?= htmlspecialchars($news['title']) ?>"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <?php else: ?>
                        <div class="w-full h-full bg-gradient-to-br from-gray-300 to-gray-400 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Category Badge -->
                    <div class="absolute top-4 left-4">
                        <span class="px-3 py-1.5 bg-blue-600 text-white text-xs font-bold rounded-lg shadow-lg">
                            <?= htmlspecialchars($news['category_name'] ?? 'Berita') ?>
                        </span>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="p-6">
                    <h4 class="text-lg font-bold text-gray-900 mb-3 line-clamp-2 leading-tight group-hover:text-blue-600 transition-colors">
                        <a href="<?= BASE_URL ?>?route=news/detail&id=<?= $news['id_news'] ?>">
                            <?= htmlspecialchars($news['title']) ?>
                        </a>
                    </h4>
                    
                    <!-- Meta Info -->
                    <div class="space-y-2 mb-4 pb-4 border-b border-gray-100">
                        <div class="flex items-center text-xs text-gray-600">
                            <!-- Author Avatar -->
                            <?php if (!empty($news['avatar_url'])): ?>
                                <img src="/news-gate/public/<?= htmlspecialchars($news['avatar_url']) ?>" 
                                     alt="<?= htmlspecialchars($news['full_name'] ?? $news['username']) ?>"
                                     class="w-6 h-6 rounded-full object-cover mr-2 border border-gray-200">
                            <?php else: ?>
                                <div class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-[10px] mr-2">
                                    <?= strtoupper(substr($news['full_name'] ?? $news['username'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                            <span class="font-medium"><?= htmlspecialchars($news['full_name'] ?? $news['username']) ?></span>
                        </div>
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <?= date('d M Y', strtotime($news['created_at'])) ?>
                            </span>
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <?= number_format($news['views']) ?>
                            </span>
                        </div>
                    </div>
                    
                    <!-- Read More Button -->
                    <a href="<?= BASE_URL ?>?route=news/detail&id=<?= $news['id_news'] ?>" 
                       class="flex items-center justify-center w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-2.5 px-4 rounded-lg transition-all duration-200 group-hover:shadow-md">
                        <span>Baca Artikel</span>
                        <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="flex items-center justify-center space-x-2">
            <!-- Previous Button -->
            <?php if ($page > 1): ?>
            <a href="?route=news<?= !empty($categoryId) ? '&category=' . $categoryId : '' ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>&sort=<?= $sortBy ?>&page=<?= $page - 1 ?>" 
               class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <?php endif; ?>

            <!-- Page Numbers -->
            <?php
            $startPage = max(1, $page - 2);
            $endPage = min($totalPages, $page + 2);
            
            for ($i = $startPage; $i <= $endPage; $i++):
            ?>
            <a href="?route=news<?= !empty($categoryId) ? '&category=' . $categoryId : '' ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>&sort=<?= $sortBy ?>&page=<?= $i ?>" 
               class="px-4 py-2 <?= $i == $page ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' ?> border border-gray-300 rounded-lg transition font-medium">
                <?= $i ?>
            </a>
            <?php endfor; ?>

            <!-- Next Button -->
            <?php if ($page < $totalPages): ?>
            <a href="?route=news<?= !empty($categoryId) ? '&category=' . $categoryId : '' ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>&sort=<?= $sortBy ?>&page=<?= $page + 1 ?>" 
               class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <?php else: ?>
        <!-- Empty State -->
        <div class="text-center py-16">
            <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="text-2xl font-bold text-gray-700 mb-2">Tidak Ada Berita</h3>
            <p class="text-gray-500 mb-6">
                <?php if (!empty($search)): ?>
                    Tidak ditemukan berita dengan kata kunci "<?= htmlspecialchars($search) ?>"
                <?php elseif (!empty($categoryId)): ?>
                    Belum ada berita dalam kategori ini
                <?php else: ?>
                    Belum ada berita yang dipublikasikan
                <?php endif; ?>
            </p>
            <a href="<?= BASE_URL ?>?route=home" 
               class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Kembali ke Beranda
            </a>
        </div>
        <?php endif; ?>

    </main>

    <!-- Footer -->
    <footer class="bg-white shadow-lg mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <p class="text-center text-gray-600">&copy; 2025 News Gate. All rights reserved.</p>
        </div>
    </footer>

    <!-- Filter Script -->
    <script>
        // Handle category filter change
        document.getElementById('categoryFilter').addEventListener('change', function() {
            applyFilters();
        });

        // Handle sort filter change
        document.getElementById('sortFilter').addEventListener('change', function() {
            applyFilters();
        });

        function applyFilters() {
            const category = document.getElementById('categoryFilter').value;
            const sort = document.getElementById('sortFilter').value;
            const urlParams = new URLSearchParams(window.location.search);
            const search = urlParams.get('search') || '';

            let url = '<?= BASE_URL ?>?route=news';
            
            if (category) {
                url += '&category=' + category;
            }
            
            if (search) {
                url += '&search=' + encodeURIComponent(search);
            }
            
            if (sort && sort !== 'latest') {
                url += '&sort=' + sort;
            }

            window.location.href = url;
        }
    </script>
</body>
</html>
