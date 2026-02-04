# Layanan Kesehatan Digital (UAS Pemograman Web 1)

## Struktur Database (MySQL)

Jalankan SQL berikut di phpMyAdmin/XAMPP untuk membuat tabel users:

```sql
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `no_hp` VARCHAR(20) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('user','admin') NOT NULL DEFAULT 'user'
);
```

Untuk login admin, tambahkan data admin secara manual:

```sql
INSERT INTO users (nama, email, no_hp, password, role) VALUES ('Admin', 'admin@email.com', '08123456789', '<isi_hash_password>', 'admin');
```

Untuk mendapatkan hash password admin, gunakan kode PHP berikut:

```php
<?php echo password_hash('admin123', PASSWORD_BCRYPT); ?>
```

---

## Struktur Database Lanjutan

Jalankan SQL berikut di phpMyAdmin/XAMPP untuk membuat tabel utama:

```sql
-- Tabel dokter
CREATE TABLE `doctors` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama` VARCHAR(100) NOT NULL,
  `spesialis` VARCHAR(100) NOT NULL,
  `tarif` INT NOT NULL,
  `foto` VARCHAR(255),
  `deskripsi` TEXT
);

-- Tabel jadwal dokter
CREATE TABLE `schedules` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `doctor_id` INT NOT NULL,
  `hari` VARCHAR(20) NOT NULL,
  `jam_mulai` TIME NOT NULL,
  `jam_selesai` TIME NOT NULL,
  FOREIGN KEY (`doctor_id`) REFERENCES doctors(`id`) ON DELETE CASCADE
);

-- Tabel artikel edukasi
CREATE TABLE `articles` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `judul` VARCHAR(200) NOT NULL,
  `isi` TEXT NOT NULL,
  `gambar` VARCHAR(255),
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabel booking
CREATE TABLE `bookings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `doctor_id` INT NOT NULL,
  `schedule_id` INT NOT NULL,
  `keluhan` VARCHAR(255),
  `status` ENUM('pending','confirmed','done','cancelled') DEFAULT 'pending',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES users(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`doctor_id`) REFERENCES doctors(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`schedule_id`) REFERENCES schedules(`id`) ON DELETE CASCADE
);
```

Lanjutkan migrasi sesuai kebutuhan fitur lain.
