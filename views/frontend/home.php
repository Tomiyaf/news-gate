<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Gate - Portal Berita</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Navbar Component -->
    <?php require_once __DIR__ . '/../components/navbar.php'; ?>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Content Area (Main) -->
            <div class="lg:col-span-8">
                
                <?php if ($heroNews): ?>
                <!-- Hero Section - Top News -->
                <section class="mb-8">
                    <div class="relative bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-md transition duration-200">
                        <!-- Hero Image -->
                        <div class="relative h-96 overflow-hidden">
                            <?php if (!empty($heroNews['thumbnail_url'])): ?>
                                <img src="/news-gate/public/<?= htmlspecialchars($heroNews['thumbnail_url']) ?>" 
                                     alt="<?= htmlspecialchars($heroNews['title']) ?>"
                                     class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600"></div>
                            <?php endif; ?>
                            
                            <!-- Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                            
                            <!-- Badge -->
                            <div class="absolute top-6 left-6">
                                <span class="bg-red-600 text-white px-4 py-2 rounded-full text-sm font-bold shadow-lg flex items-center space-x-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span>Trending</span>
                                </span>
                            </div>
                            
                            <!-- Content Overlay -->
                            <div class="absolute bottom-0 left-0 right-0 p-8">
                                <div class="flex items-center space-x-3 mb-3">
                                    <span class="px-3 py-1 bg-blue-600 text-white text-sm font-semibold rounded-full">
                                        <?= htmlspecialchars($heroNews['category_name'] ?? 'Berita') ?>
                                    </span>
                                    <span class="text-white text-sm flex items-center">
                                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <?= number_format($heroNews['views']) ?>
                                    </span>
                                </div>
                                
                                <h2 class="text-3xl md:text-4xl font-bold text-white mb-3 leading-tight">
                                    <a href="<?= BASE_URL ?>?route=news/detail&id=<?= $heroNews['id_news'] ?>" class="hover:text-blue-300 transition">
                                        <?= htmlspecialchars($heroNews['title']) ?>
                                    </a>
                                </h2>
                                
                                <p class="text-gray-200 text-lg mb-4 line-clamp-2">
                                    <?= htmlspecialchars(substr(strip_tags($heroNews['content']), 0, 150)) ?>...
                                </p>
                                
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center text-gray-300 text-sm">
                                        <span class="mr-4"><?= htmlspecialchars($heroNews['full_name'] ?? $heroNews['username']) ?></span>
                                        <span><?= date('d M Y', strtotime($heroNews['created_at'])) ?></span>
                                    </div>
                                    <a href="<?= BASE_URL ?>?route=news/detail&id=<?= $heroNews['id_news'] ?>" 
                                       class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition duration-200">
                                        <span>Baca Selengkapnya</span>
                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <?php endif; ?>

                <!-- Latest News Section -->
                <?php if (!empty($latestNews)): ?>
                <section>
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Berita Terbaru
                        </h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($latestNews as $news): ?>
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
                </section>
                <?php endif; ?>
                
            </div>

            <!-- Right Sidebar -->
            <aside class="lg:col-span-4">
                <div class="space-y-6">
                    
                    <!-- Trending News Widget -->
                    <?php if (!empty($trendingNews)): ?>
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h4 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            Trending
                        </h4>
                        
                        <div class="space-y-3">
                            <?php $rank = 1; foreach ($trendingNews as $trending): ?>
                            <a href="<?= BASE_URL ?>?route=news/detail&id=<?= $trending['id_news'] ?>" 
                               class="flex items-start space-x-3 hover:bg-gray-50 p-3 rounded-lg transition group">
                                <!-- Rank Number -->
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-md flex items-center justify-center font-bold text-sm">
                                    <?= $rank++ ?>
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <h5 class="text-sm font-semibold text-gray-900 line-clamp-2 group-hover:text-blue-600 transition leading-snug">
                                        <?= htmlspecialchars($trending['title']) ?>
                                    </h5>
                                    <p class="text-xs text-gray-500 mt-1 flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <?= number_format($trending['views']) ?>
                                    </p>
                                </div>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Popular Categories Widget -->
                    <?php if (!empty($popularCategories)): ?>
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h4 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            Kategori Populer
                        </h4>
                        
                        <div class="space-y-2">
                            <?php foreach ($popularCategories as $cat): ?>
                            <a href="<?= BASE_URL ?>?route=news&category=<?= $cat['id_category'] ?>" 
                               class="flex items-center justify-between p-3 hover:bg-blue-50 rounded-lg transition group">
                                <span class="text-gray-700 font-medium group-hover:text-blue-600 transition">
                                    <?= htmlspecialchars($cat['name']) ?>
                                </span>
                                <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full group-hover:bg-blue-100 group-hover:text-blue-600 transition">
                                    <?= $cat['total'] ?>
                                </span>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                </div>
            </aside>
            
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white shadow-lg mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <p class="text-center text-gray-600">&copy; 2025 News Gate. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
