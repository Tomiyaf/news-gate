<?php 
$pageTitle = 'Edit Berita';
$currentPage = 'news';
include __DIR__ . '/../layouts/header.php';
include __DIR__ . '/../layouts/sidebar.php';
?>

            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                    <a href="<?= BASE_URL ?>?route=admin/news" class="hover:text-blue-600">Kelola Berita</a>
                    <span>/</span>
                    <span class="text-gray-900">Edit Berita</span>
                </div>
                <h2 class="text-3xl font-bold text-gray-800">Edit Berita</h2>
            </div>

            <!-- Error Messages -->
            <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
            <?php endif; ?>

            <!-- Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <form method="POST" action="<?= BASE_URL ?>?route=admin/news/update&id=<?= $news['id_news'] ?>" enctype="multipart/form-data">
                    
                    <!-- Title -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Judul Berita <span class="text-red-500">*</span></label>
                        <input type="text" 
                               name="title" 
                               required
                               value="<?= htmlspecialchars($news['title']) ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Masukkan judul berita">
                    </div>

                    <!-- Category -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                        <select name="id_category" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id_category'] ?>" <?= $news['id_category'] == $category['id_category'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['parent_name']) ?> → <?= htmlspecialchars($category['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Hanya sub-kategori yang dapat dipilih</p>
                    </div>

                    <!-- Current Thumbnail -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Thumbnail Saat Ini</label>
                        <?php if (!empty($news['thumbnail_url'])): ?>
                        <div class="mb-4">
                            <img src="/news-gate/public/<?= htmlspecialchars($news['thumbnail_url']) ?>" 
                                 alt="Current thumbnail"
                                 class="max-w-xs h-48 rounded-lg object-cover border border-gray-300">
                        </div>
                        <?php else: ?>
                        <div class="mb-4">
                            <div class="w-48 h-48 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-300">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- New Thumbnail -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ganti Thumbnail (Opsional)</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                            <input type="file" 
                                   name="thumbnail" 
                                   id="thumbnailInput"
                                   accept="image/jpeg,image/png,image/gif,image/webp"
                                   class="hidden"
                                   onchange="previewImage(this)">
                            <label for="thumbnailInput" class="cursor-pointer">
                                <div id="thumbnailPreview" class="mb-3">
                                    <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <p class="text-gray-600 font-medium">Klik untuk upload thumbnail baru</p>
                                <p class="text-sm text-gray-500 mt-1">Format: JPEG, PNG, GIF, WebP (Max 5MB)</p>
                            </label>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti thumbnail</p>
                    </div>

                    <!-- Content -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Konten Berita <span class="text-red-500">*</span></label>
                        <div id="editor" class="bg-white" style="min-height: 400px;"></div>
                        <textarea name="content" id="content" style="display:none;"><?= htmlspecialchars($news['content']) ?></textarea>
                        <p class="text-xs text-gray-500 mt-1">Gunakan toolbar untuk formatting teks</p>
                    </div>

                    <!-- Status -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                        <div class="flex gap-4">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="radio" 
                                       name="status" 
                                       value="draft" 
                                       <?= $news['status'] == 'draft' ? 'checked' : '' ?>
                                       class="w-4 h-4 text-blue-600">
                                <span class="text-gray-700">Draft</span>
                            </label>
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="radio" 
                                       name="status" 
                                       value="published"
                                       <?= $news['status'] == 'published' ? 'checked' : '' ?>
                                       class="w-4 h-4 text-blue-600">
                                <span class="text-gray-700">Published</span>
                            </label>
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="radio" 
                                       name="status" 
                                       value="archived"
                                       <?= $news['status'] == 'archived' ? 'checked' : '' ?>
                                       class="w-4 h-4 text-blue-600">
                                <span class="text-gray-700">Archived</span>
                            </label>
                        </div>
                    </div>

                    <!-- Meta Info -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Informasi Berita</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm text-gray-600">
                            <div>
                                <span class="font-medium">Penulis:</span> 
                                <?= htmlspecialchars($news['full_name'] ?? $news['username']) ?>
                            </div>
                            <div>
                                <span class="font-medium">Views:</span> 
                                <?= $news['views'] ?>
                            </div>
                            <div>
                                <span class="font-medium">Dibuat:</span> 
                                <?= date('d M Y H:i', strtotime($news['created_at'])) ?>
                            </div>
                            <div>
                                <span class="font-medium">Terakhir Update:</span> 
                                <?= date('d M Y H:i', strtotime($news['updated_at'])) ?>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center space-x-3 pt-6 border-t">
                        <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Update Berita
                        </button>
                        <a href="<?= BASE_URL ?>?route=admin/news" class="bg-gray-200 text-gray-700 px-8 py-3 rounded-lg hover:bg-gray-300 transition">
                            Batal
                        </a>
                    </div>
                </form>
            </div>

            <!-- Quill CSS -->
            <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
            <style>
                .ql-container {
                    font-size: 16px;
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                }
                .ql-editor {
                    min-height: 400px;
                    max-height: 600px;
                    overflow-y: auto;
                }
                .ql-toolbar {
                    background: #f8f9fa;
                    border-radius: 8px 8px 0 0;
                    border: 1px solid #e5e7eb !important;
                }
                .ql-container {
                    border-radius: 0 0 8px 8px;
                    border: 1px solid #e5e7eb !important;
                    border-top: none !important;
                }
                .ql-editor.ql-blank::before {
                    color: #9ca3af;
                    font-style: normal;
                }
            </style>
            
            <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
            <script>
            // Initialize Quill Editor
            var quill = new Quill('#editor', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                        [{ 'font': [] }],
                        [{ 'size': ['small', false, 'large', 'huge'] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'script': 'sub'}, { 'script': 'super' }],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'indent': '-1'}, { 'indent': '+1' }],
                        [{ 'align': [] }],
                        ['blockquote', 'code-block'],
                        ['link'],
                        ['clean']
                    ]
                },
                placeholder: 'Tulis konten berita di sini...'
            });
            
            // Load existing content
            var existingContent = document.querySelector('#content').value;
            if (existingContent) {
                quill.root.innerHTML = existingContent;
            }
            
            // Sync Quill content to hidden textarea on form submit
            document.querySelector('form').onsubmit = function() {
                var content = quill.root.innerHTML;
                document.querySelector('#content').value = content;
                
                // Validate content not empty
                if (quill.getText().trim().length === 0) {
                    alert('Konten berita tidak boleh kosong!');
                    return false;
                }
            };
            
            function previewImage(input) {
                const preview = document.getElementById('thumbnailPreview');
                const file = input.files[0];
                
                if (file) {
                    // Validate size
                    if (file.size > 5 * 1024 * 1024) {
                        alert('Ukuran file terlalu besar! Maksimal 5MB');
                        input.value = '';
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.innerHTML = `<img src="${e.target.result}" class="max-w-full h-48 mx-auto rounded-lg object-cover">`;
                    }
                    reader.readAsDataURL(file);
                }
            }
            </script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
