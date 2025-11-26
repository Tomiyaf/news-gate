# News Gate - Portal Berita

Portal berita sederhana menggunakan PHP Native dan Tailwind CSS.

## Struktur Folder

```
news-gate/
├── app/
│   ├── config/
│   │   ├── Database.php      # Konfigurasi database
│   │   └── config.php         # Konfigurasi umum aplikasi
│   ├── controllers/
│   │   ├── AuthController.php # Controller untuk autentikasi
│   │   └── HomeController.php # Controller untuk halaman home
│   ├── models/
│   │   └── User.php           # Model User
│   └── Router.php             # Routing system
├── public/
│   ├── css/                   # Folder untuk CSS custom (jika diperlukan)
│   └── index.php              # Entry point aplikasi
└── views/
    ├── home.php               # Halaman home
    └── login.php              # Halaman login
```

## Setup Database

1. Buat database dengan nama `news_db`
2. Buat tabel users dengan query berikut:

```sql
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

3. Insert data user untuk testing:

```sql
-- Password: admin123
INSERT INTO `users` (`username`, `password`, `email`)
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@newsgate.com');
```

## Konfigurasi

Edit file `app/config/Database.php` jika diperlukan:

- Host: localhost
- Database: news_db
- Username: root
- Password: (kosong)

## Cara Menjalankan

1. Pastikan Laragon sudah berjalan
2. Akses aplikasi melalui browser: `http://localhost/news-gate/public/`
3. Untuk login gunakan:
   - Username: `admin`
   - Password: `admin123`

## Fitur

- ✅ Halaman Home dengan button login
- ✅ Halaman Login dengan form username dan password
- ✅ Sistem autentikasi sederhana
- ✅ Routing system
- ✅ Session management
- ✅ Styling dengan Tailwind CSS (via CDN)

## Tech Stack

- PHP Native
- MySQL
- Tailwind CSS (CDN)
- PDO untuk database connection
