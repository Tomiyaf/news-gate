<!-- Navbar Component -->
<nav class="bg-white shadow-lg sticky top-0 z-50">
    <!-- Top Layer - Logo, Search, Profile -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="<?= BASE_URL ?>" class="text-2xl font-bold text-blue-600 hover:text-blue-700 transition">
                        News Gate
                    </a>
                </div>

                <!-- Spacer -->
                <div class="flex-1"></div>

                <!-- Search Bar & User Menu -->
                <div class="flex items-center space-x-4">
                    <!-- Search Bar -->
                    <div class="hidden md:block">
                        <div class="relative">
                            <input 
                                type="text" 
                                id="searchInput"
                                placeholder="Cari berita..." 
                                class="w-64 px-4 py-2 pl-10 pr-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                            >
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                        <!-- Profile Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button 
                                @click="open = !open"
                                @click.away="open = false"
                                class="flex items-center space-x-2 focus:outline-none group"
                            >
                                <!-- Avatar -->
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center ring-2 ring-transparent group-hover:ring-blue-400 transition">
                                    <?php if (!empty($_SESSION['avatar_url'])): ?>
                                        <img src="<?= htmlspecialchars($_SESSION['avatar_url']) ?>" 
                                             alt="Avatar" 
                                             class="w-full h-full rounded-full object-cover">
                                    <?php else: ?>
                                        <span class="text-white font-bold text-lg">
                                            <?= strtoupper(substr($_SESSION['username'], 0, 1)) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Dropdown Icon -->
                                <svg class="w-4 h-4 text-gray-600 group-hover:text-blue-600 transition" 
                                     :class="{ 'rotate-180': open }"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div 
                                x-show="open"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-200 py-2"
                                style="display: none;"
                            >
                                <!-- User Info -->
                                <div class="px-4 py-3 border-b border-gray-200">
                                    <p class="text-sm font-semibold text-gray-900">
                                        <?= htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username']) ?>
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        <?= htmlspecialchars($_SESSION['email'] ?? '') ?>
                                    </p>
                                    <?php if (isset($_SESSION['id_role'])): ?>
                                        <span class="inline-block mt-2 px-2 py-1 text-xs font-semibold rounded-full 
                                            <?= $_SESSION['id_role'] == 1 ? 'bg-purple-100 text-purple-800' : ($_SESSION['id_role'] == 2 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') ?>">
                                            <?= $_SESSION['id_role'] == 1 ? 'Admin' : ($_SESSION['id_role'] == 2 ? 'Editor' : 'User') ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <!-- Menu Items -->
                                <div class="py-2">
                                    <a href="<?= BASE_URL ?>?route=profile" 
                                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        Profil Saya
                                    </a>

                                    <?php if (isset($_SESSION['id_role']) && in_array($_SESSION['id_role'], [1, 2])): ?>
                                        <a href="<?= BASE_URL ?>?route=admin/dashboard" 
                                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                            </svg>
                                            Admin Panel
                                        </a>
                                    <?php endif; ?>

                                    <a href="<?= BASE_URL ?>?route=bookmarks" 
                                       class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                        </svg>
                                        Bookmark
                                    </a>
                                </div>

                                <!-- Logout -->
                                <div class="border-t border-gray-200 pt-2">
                                    <a href="<?= BASE_URL ?>?route=logout" 
                                       class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Logout
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Login & Register Buttons -->
                        <a href="<?= BASE_URL ?>?route=login" 
                           class="px-4 py-2 text-blue-600 hover:text-blue-700 font-medium transition">
                            Login
                        </a>
                        <a href="<?= BASE_URL ?>?route=register" 
                           class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-200">
                            Register
                        </a>
                    <?php endif; ?>
                </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Layer - Categories Navigation -->
    <div class="bg-blue-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center space-x-1">
                <!-- Category Menus with Hover Dropdowns -->
                <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $parentCategory): ?>
                        <?php if (empty($parentCategory['id_parent'])): ?>
                            <?php 
                            // Get subcategories for this parent
                            $subCategories = array_filter($categories, function($cat) use ($parentCategory) {
                                return $cat['id_parent'] == $parentCategory['id_category'];
                            });
                            ?>
                            
                            <div class="relative group">
                                <!-- Parent Category (Not Clickable) -->
                                <button class="flex items-center px-4 py-3 text-white hover:bg-blue-700 transition whitespace-nowrap font-medium">
                                    <span><?= htmlspecialchars($parentCategory['name']) ?></span>
                                    <?php if (!empty($subCategories)): ?>
                                        <svg class="w-4 h-4 ml-1 transform group-hover:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    <?php endif; ?>
                                </button>

                                <!-- Subcategories Dropdown -->
                                <?php if (!empty($subCategories)): ?>
                                    <div class="absolute left-0 top-full w-64 bg-blue-600 rounded-b-lg shadow-2xl border border-blue-700 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                                        <?php foreach ($subCategories as $subCategory): ?>
                                            <a href="<?= BASE_URL ?>?route=news&category=<?= $subCategory['id_category'] ?>" 
                                               class="block px-4 py-3 text-sm text-white hover:bg-blue-700 transition-colors duration-150">
                                                <?= htmlspecialchars($subCategory['name']) ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- Alpine.js for dropdown functionality -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Search functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const query = this.value.trim();
                if (query) {
                    window.location.href = '<?= BASE_URL ?>?route=news&search=' + encodeURIComponent(query);
                }
            }
        });
    }
});
</script>
