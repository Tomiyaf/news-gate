        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg min-h-screen">
            <div class="p-6">
                <nav class="space-y-2">
                    <a href="<?php echo BASE_URL; ?>?route=admin/dashboard" 
                       class="flex items-center px-4 py-3 text-gray-700 rounded-lg <?= ($currentPage ?? '') == 'dashboard' ? 'bg-blue-50 font-semibold' : 'hover:bg-gray-50' ?>">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>
                    
                    <a href="<?php echo BASE_URL; ?>?route=admin/news" 
                       class="flex items-center px-4 py-3 text-gray-700 rounded-lg <?= ($currentPage ?? '') == 'news' ? 'bg-blue-50 font-semibold' : 'hover:bg-gray-50' ?>">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                        Kelola Berita
                    </a>
                    
                    <?php if ($_SESSION['id_role'] == 1): ?>
                    <a href="<?php echo BASE_URL; ?>?route=admin/categories" 
                       class="flex items-center px-4 py-3 text-gray-700 rounded-lg <?= ($currentPage ?? '') == 'categories' ? 'bg-blue-50 font-semibold' : 'hover:bg-gray-50' ?>">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Kategori
                    </a>
                    
                    <a href="<?php echo BASE_URL; ?>?route=admin/users" 
                       class="flex items-center px-4 py-3 text-gray-700 rounded-lg <?= ($currentPage ?? '') == 'users' ? 'bg-blue-50 font-semibold' : 'hover:bg-gray-50' ?>">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Kelola User
                    </a>
                    <?php endif; ?>
                    
                    <hr class="my-4">
                    
                    <a href="<?php echo BASE_URL; ?>" target="_blank" 
                       class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        Lihat Website
                    </a>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-8">
