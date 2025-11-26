<?php 
$pageTitle = 'Dashboard';
$currentPage = 'dashboard';
include __DIR__ . '/layouts/header.php';
include __DIR__ . '/layouts/sidebar.php';
?>
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Dashboard</h2>
                <p class="text-gray-600 mt-1">Selamat datang di panel admin News Gate</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-full">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Total Berita</p>
                            <p class="text-2xl font-bold text-gray-900"><?= $stats['total'] ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-full">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Published</p>
                            <p class="text-2xl font-bold text-gray-900"><?= $stats['published'] ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-full">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Draft</p>
                            <p class="text-2xl font-bold text-gray-900"><?= $stats['draft'] ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-full">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-600 text-sm">Total Views</p>
                            <p class="text-2xl font-bold text-gray-900"><?= number_format($stats['total_views']) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Welcome Message -->
            <div class="bg-white rounded-lg shadow p-8 mb-8">
                <h3 class="text-xl font-semibold mb-4">Selamat Datang! 👋</h3>
                <p class="text-gray-600 mb-4">
                    Anda login sebagai <strong><?php echo $_SESSION['id_role'] == 1 ? 'Admin' : 'Editor'; ?></strong>. 
                    Dari dashboard ini Anda dapat mengelola konten berita News Gate.
                </p>
                <div class="flex gap-4">
                    <a href="<?php echo BASE_URL; ?>?route=admin/news/create" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition duration-200">
                        Buat Berita Baru
                    </a>
                    <a href="<?php echo BASE_URL; ?>?route=admin/news" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg transition duration-200">
                        Lihat Semua Berita
                    </a>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Aktivitas Terbaru</h3>
                <p class="text-gray-500 text-center py-8">Belum ada aktivitas</p>
            </div>

<?php include __DIR__ . '/layouts/footer.php'; ?>
