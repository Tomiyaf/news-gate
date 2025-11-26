<?php 
$pageTitle = 'Tambah Kategori';
$currentPage = 'categories';
include __DIR__ . '/../layouts/header.php';
include __DIR__ . '/../layouts/sidebar.php';
?>

            <!-- Header -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Tambah Kategori</h2>
                <p class="text-gray-600 mt-1">Create new category or sub-category</p>
            </div>

            <!-- Error Messages -->
            <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
            <?php endif; ?>

            <!-- Form -->
            <div class="bg-white rounded-lg shadow-md p-8 max-w-2xl">
                <form action="<?= BASE_URL ?>?route=admin/categories/store" method="POST">
                    <!-- Name -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                            Nama Kategori <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               placeholder="Masukkan nama kategori">
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                            Deskripsi
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                  placeholder="Masukkan deskripsi kategori (opsional)"></textarea>
                    </div>

                    <!-- Parent Category -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="id_parent">
                            Parent Kategori
                        </label>
                        <select id="id_parent" 
                                name="id_parent" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Tidak Ada (Kategori Utama) --</option>
                            <?php foreach ($parent_categories as $parent): ?>
                            <option value="<?= $parent['id_category'] ?>"><?= htmlspecialchars($parent['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="text-sm text-gray-500 mt-1">Pilih parent jika ini adalah sub kategori</p>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-between">
                        <a href="<?= BASE_URL ?>?route=admin/categories" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
