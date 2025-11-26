<?php 
$pageTitle = 'Tambah Berita';
$currentPage = 'news';
include __DIR__ . '/../layouts/header.php';
include __DIR__ . '/../layouts/sidebar.php';
?>

            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                    <a href="<?= BASE_URL ?>?route=admin/news" class="hover:text-blue-600">Kelola Berita</a>
                    <span>/</span>
                    <span class="text-gray-900">Tambah Berita</span>
                </div>
                <h2 class="text-3xl font-bold text-gray-800">Tambah Berita</h2>
            </div>

            <!-- Error Messages -->
            <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
            <?php endif; ?>

            <!-- Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <form method="POST" action="<?= BASE_URL ?>?route=admin/news/store" enctype="multipart/form-data">
                    
                    <!-- Title -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Judul Berita <span class="text-red-500">*</span></label>
                        <input type="text" 
                               name="title" 
                               required
                               value="<?= htmlspecialchars($_POST['title'] ?? '') ?>"
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
                            <option value="<?= $category['id_category'] ?>" <?= ($_POST['id_category'] ?? '') == $category['id_category'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($category['parent_name']) ?> → <?= htmlspecialchars($category['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Hanya sub-kategori yang dapat dipilih</p>
                    </div>

                    <!-- Thumbnail -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Thumbnail <span class="text-red-500">*</span></label>
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
                                <p class="text-gray-600 font-medium">Klik untuk upload thumbnail</p>
                                <p class="text-sm text-gray-500 mt-1">Format: JPEG, PNG, GIF, WebP (Max 5MB)</p>
                            </label>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Konten Berita <span class="text-red-500">*</span></label>
                        <div id="editor" class="bg-white" style="min-height: 400px;"></div>
                        <textarea name="content" id="content" style="display:none;"></textarea>
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
                                       <?= ($_POST['status'] ?? 'draft') == 'draft' ? 'checked' : '' ?>
                                       class="w-4 h-4 text-blue-600">
                                <span class="text-gray-700">Draft</span>
                            </label>
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="radio" 
                                       name="status" 
                                       value="published"
                                       <?= ($_POST['status'] ?? '') == 'published' ? 'checked' : '' ?>
                                       class="w-4 h-4 text-blue-600">
                                <span class="text-gray-700">Published</span>
                            </label>
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="radio" 
                                       name="status" 
                                       value="archived"
                                       <?= ($_POST['status'] ?? '') == 'archived' ? 'checked' : '' ?>
                                       class="w-4 h-4 text-blue-600">
                                <span class="text-gray-700">Archived</span>
                            </label>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center space-x-3 pt-6 border-t">
                        <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan Berita
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
            
            // Sync Quill content to hidden textarea on form submit
            document.querySelector('form').onsubmit = function(e) {
                console.log('Form submit triggered');
                
                var content = quill.root.innerHTML;
                console.log('Quill content:', content);
                
                document.querySelector('#content').value = content;
                console.log('Hidden textarea value:', document.querySelector('#content').value);
                
                // Validate content not empty
                if (quill.getText().trim().length === 0) {
                    alert('Konten berita tidak boleh kosong!');
                    return false;
                }
                
                // Validate thumbnail uploaded
                var thumbnailInput = document.querySelector('#thumbnailInput');
                if (!thumbnailInput.files || thumbnailInput.files.length === 0) {
                    alert('Thumbnail harus diupload!');
                    return false;
                }
                
                console.log('Form validation passed, submitting...');
                return true;
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
