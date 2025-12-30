-- =====================================================
-- E-PENOMORAN DATABASE SCHEMA
-- Sistem Penomoran Surat Digital
-- Generated: 2025-12-23
-- =====================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";

-- =====================================================
-- TABLE: users
-- Tabel untuk menyimpan data pengguna sistem
-- =====================================================
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `nip` VARCHAR(20) DEFAULT NULL,
    `no_hp` VARCHAR(15) DEFAULT NULL,
    `jabatan` VARCHAR(255) DEFAULT NULL,
    `pangkat` VARCHAR(255) DEFAULT NULL,
    `bidang` ENUM('Sekretariat', 'Bidang TIK dan Persandian', 'Bidang IKPS', 'Bidang Aptika') DEFAULT NULL,
    `role` ENUM('admin', 'pemimpin', 'operator', 'pegawai', 'admin_surat_masuk', 'admin_surat_keluar') DEFAULT 'pegawai',
    `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
    `password` VARCHAR(255) NOT NULL,
    `remember_token` VARCHAR(100) DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_email_unique` (`email`),
    UNIQUE KEY `users_nip_unique` (`nip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: password_reset_tokens
-- Tabel untuk token reset password
-- =====================================================
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
    `email` VARCHAR(255) NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: sessions
-- Tabel untuk session management
-- =====================================================
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
    `id` VARCHAR(255) NOT NULL,
    `user_id` BIGINT UNSIGNED DEFAULT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `user_agent` TEXT DEFAULT NULL,
    `payload` LONGTEXT NOT NULL,
    `last_activity` INT NOT NULL,
    PRIMARY KEY (`id`),
    KEY `sessions_user_id_index` (`user_id`),
    KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: cache
-- Tabel untuk cache data
-- =====================================================
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
    `key` VARCHAR(255) NOT NULL,
    `value` MEDIUMTEXT NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: cache_locks
-- Tabel untuk cache locks
-- =====================================================
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
    `key` VARCHAR(255) NOT NULL,
    `owner` VARCHAR(255) NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: jobs
-- Tabel untuk queue jobs
-- =====================================================
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `queue` VARCHAR(255) NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `attempts` TINYINT UNSIGNED NOT NULL,
    `reserved_at` INT UNSIGNED DEFAULT NULL,
    `available_at` INT UNSIGNED NOT NULL,
    `created_at` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: job_batches
-- Tabel untuk batch jobs
-- =====================================================
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
    `id` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `total_jobs` INT NOT NULL,
    `pending_jobs` INT NOT NULL,
    `failed_jobs` INT NOT NULL,
    `failed_job_ids` LONGTEXT NOT NULL,
    `options` MEDIUMTEXT DEFAULT NULL,
    `cancelled_at` INT DEFAULT NULL,
    `created_at` INT NOT NULL,
    `finished_at` INT DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: failed_jobs
-- Tabel untuk failed queue jobs
-- =====================================================
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `uuid` VARCHAR(255) NOT NULL,
    `connection` TEXT NOT NULL,
    `queue` TEXT NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `exception` LONGTEXT NOT NULL,
    `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: tipe_surats
-- Tabel untuk menyimpan jenis/tipe surat
-- =====================================================
DROP TABLE IF EXISTS `tipe_surats`;
CREATE TABLE `tipe_surats` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `jenis_surat` VARCHAR(255) NOT NULL,
    `format_penomoran` VARCHAR(255) NOT NULL,
    `nomor_terakhir` INT NOT NULL DEFAULT 0,
    `last_reset_year` YEAR DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `tipe_surats_id_last_reset_year_index` (`id`, `last_reset_year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: surats (Surat Keluar)
-- Tabel untuk menyimpan data surat keluar
-- =====================================================
DROP TABLE IF EXISTS `surats`;
CREATE TABLE `surats` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `tipe_surat_id` BIGINT UNSIGNED NOT NULL,
    `tanggal_surat` DATE NOT NULL,
    `tujuan` VARCHAR(255) NOT NULL,
    `perihal` TEXT NOT NULL,
    `isi_surat` TEXT DEFAULT NULL,
    `file_surat` VARCHAR(255) DEFAULT NULL,
    `file_surat_original_name` VARCHAR(255) DEFAULT NULL,
    `metode_pembuatan` ENUM('Srikandi', 'TTE', 'Manual') DEFAULT 'Manual',
    `nomor_urut` INT DEFAULT NULL,
    `nomor_surat_full` VARCHAR(255) DEFAULT NULL,
    `status` ENUM('0', '1', '2') DEFAULT '0' COMMENT '0=pending, 1=approved, 2=rejected',
    `approved_at` TIMESTAMP NULL DEFAULT NULL,
    `approved_by` BIGINT UNSIGNED DEFAULT NULL,
    `rejected_at` TIMESTAMP NULL DEFAULT NULL,
    `rejected_by` BIGINT UNSIGNED DEFAULT NULL,
    `rejection_reason` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_by` BIGINT UNSIGNED DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `unique_letter_number` (`nomor_surat_full`),
    KEY `surats_user_id_foreign` (`user_id`),
    KEY `surats_tipe_surat_id_foreign` (`tipe_surat_id`),
    KEY `surats_deleted_by_foreign` (`deleted_by`),
    KEY `surats_approved_by_foreign` (`approved_by`),
    KEY `surats_rejected_by_foreign` (`rejected_by`),
    KEY `surats_status_approved_at_index` (`status`, `approved_at`),
    KEY `surats_deleted_at_index` (`deleted_at`),
    KEY `surats_tanggal_surat_index` (`tanggal_surat`),
    KEY `surats_tipe_surat_id_tanggal_surat_index` (`tipe_surat_id`, `tanggal_surat`),
    KEY `surats_user_id_status_index` (`user_id`, `status`),
    CONSTRAINT `surats_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `surats_tipe_surat_id_foreign` FOREIGN KEY (`tipe_surat_id`) REFERENCES `tipe_surats` (`id`) ON DELETE CASCADE,
    CONSTRAINT `surats_deleted_by_foreign` FOREIGN KEY (`deleted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    CONSTRAINT `surats_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    CONSTRAINT `surats_rejected_by_foreign` FOREIGN KEY (`rejected_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: surat_masuks
-- Tabel untuk menyimpan data surat masuk
-- =====================================================
DROP TABLE IF EXISTS `surat_masuks`;
CREATE TABLE `surat_masuks` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nomor_surat` VARCHAR(255) NOT NULL,
    `tanggal_surat` DATE NOT NULL,
    `jenis_surat` VARCHAR(255) NOT NULL,
    `judul_surat` VARCHAR(255) NOT NULL,
    `isi_surat` TEXT DEFAULT NULL,
    `disposisi_pimpinan` TEXT DEFAULT NULL,
    `tanggal_disposisi` DATE DEFAULT NULL,
    `status_tindak_lanjut` ENUM('pending', 'proses', 'selesai') DEFAULT 'pending',
    `posisi_tindak_lanjut` VARCHAR(255) DEFAULT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `surat_masuks_user_id_foreign` (`user_id`),
    CONSTRAINT `surat_masuks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: surat_audit_logs
-- Tabel untuk audit trail surat
-- =====================================================
DROP TABLE IF EXISTS `surat_audit_logs`;
CREATE TABLE `surat_audit_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `surat_id` BIGINT UNSIGNED NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `action` VARCHAR(50) NOT NULL COMMENT 'created, updated, approved, rejected, deleted, restored',
    `old_status` VARCHAR(10) DEFAULT NULL,
    `new_status` VARCHAR(10) DEFAULT NULL,
    `notes` TEXT DEFAULT NULL,
    `ip_address` VARCHAR(45) NOT NULL,
    `user_agent` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `surat_audit_logs_surat_id_foreign` (`surat_id`),
    KEY `surat_audit_logs_user_id_foreign` (`user_id`),
    KEY `surat_audit_logs_surat_id_created_at_index` (`surat_id`, `created_at`),
    KEY `surat_audit_logs_user_id_action_index` (`user_id`, `action`),
    KEY `surat_audit_logs_created_at_index` (`created_at`),
    CONSTRAINT `surat_audit_logs_surat_id_foreign` FOREIGN KEY (`surat_id`) REFERENCES `surats` (`id`) ON DELETE CASCADE,
    CONSTRAINT `surat_audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: settings
-- Tabel untuk pengaturan sistem
-- =====================================================
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `key` VARCHAR(255) NOT NULL,
    `value` TEXT DEFAULT NULL,
    `type` VARCHAR(255) DEFAULT 'text' COMMENT 'text, file, textarea',
    `label` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: role_permissions
-- Tabel untuk pengaturan hak akses per role
-- =====================================================
DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE `role_permissions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `role` VARCHAR(255) NOT NULL,
    `permission` VARCHAR(255) NOT NULL,
    `enabled` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `role_permissions_role_permission_unique` (`role`, `permission`),
    KEY `role_permissions_role_index` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- TABLE: migrations (Laravel Migration Tracking)
-- =====================================================
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `migration` VARCHAR(255) NOT NULL,
    `batch` INT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;

-- =====================================================
-- SEEDER DATA: tipe_surats (Master Data Jenis Surat)
-- =====================================================
INSERT INTO `tipe_surats` (`id`, `jenis_surat`, `format_penomoran`, `nomor_terakhir`, `last_reset_year`, `created_at`, `updated_at`) VALUES
(1, 'Surat Biasa', '{NOMOR}/DISKOMINFO-SPT/{ROMAWI}/{TAHUN}', 0, 2025, NOW(), NOW()),
(2, 'Surat Keputusan', '{NOMOR}/DISKOMINFO-SK/{ROMAWI}/{TAHUN}', 0, 2025, NOW(), NOW()),
(3, 'Surat Perintah', '{NOMOR}/DISKOMINFO-SPR/{ROMAWI}/{TAHUN}', 0, 2025, NOW(), NOW()),
(4, 'Surat Tugas', '{NOMOR}/DISKOMINFO-ST/{ROMAWI}/{TAHUN}', 0, 2025, NOW(), NOW()),
(5, 'Surat Undangan', '{NOMOR}/DISKOMINFO-UND/{ROMAWI}/{TAHUN}', 0, 2025, NOW(), NOW()),
(6, 'Surat Keterangan', '{NOMOR}/DISKOMINFO-KET/{ROMAWI}/{TAHUN}', 0, 2025, NOW(), NOW()),
(7, 'Nota Dinas', '{NOMOR}/DISKOMINFO-ND/{ROMAWI}/{TAHUN}', 0, 2025, NOW(), NOW()),
(8, 'Surat Edaran', '{NOMOR}/DISKOMINFO-SE/{ROMAWI}/{TAHUN}', 0, 2025, NOW(), NOW());

-- =====================================================
-- SEEDER DATA: users (Default Admin User)
-- Password: password (bcrypt hash)
-- =====================================================
INSERT INTO `users` (`id`, `name`, `email`, `role`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@epenomoran.test', 'admin', '$2y$12$1234567890123456789012uxNSjX1234567890123456789012345', NOW(), NOW());

-- =====================================================
-- SEEDER DATA: settings (Default Settings)
-- =====================================================
INSERT INTO `settings` (`key`, `value`, `type`, `label`, `created_at`, `updated_at`) VALUES
('app_name', 'E-Penomoran Surat', 'text', 'Nama Aplikasi', NOW(), NOW()),
('institution_name', 'Dinas Komunikasi dan Informatika', 'text', 'Nama Instansi', NOW(), NOW()),
('institution_address', 'Jl. Contoh No. 123', 'textarea', 'Alamat Instansi', NOW(), NOW()),
('institution_logo', NULL, 'file', 'Logo Instansi', NOW(), NOW());

-- =====================================================
-- SEEDER DATA: role_permissions (Default Permissions)
-- =====================================================
INSERT INTO `role_permissions` (`role`, `permission`, `enabled`, `created_at`, `updated_at`) VALUES
-- Admin Surat Masuk Permissions
('admin_surat_masuk', 'surat_masuk.view', 1, NOW(), NOW()),
('admin_surat_masuk', 'surat_masuk.create', 1, NOW(), NOW()),
('admin_surat_masuk', 'surat_masuk.edit', 1, NOW(), NOW()),
('admin_surat_masuk', 'surat_masuk.delete', 1, NOW(), NOW()),
('admin_surat_masuk', 'surat_keluar.view', 0, NOW(), NOW()),
('admin_surat_masuk', 'surat_keluar.create', 0, NOW(), NOW()),
('admin_surat_masuk', 'surat_keluar.edit', 0, NOW(), NOW()),
('admin_surat_masuk', 'surat_keluar.delete', 0, NOW(), NOW()),
-- Admin Surat Keluar Permissions
('admin_surat_keluar', 'surat_masuk.view', 0, NOW(), NOW()),
('admin_surat_keluar', 'surat_masuk.create', 0, NOW(), NOW()),
('admin_surat_keluar', 'surat_masuk.edit', 0, NOW(), NOW()),
('admin_surat_keluar', 'surat_masuk.delete', 0, NOW(), NOW()),
('admin_surat_keluar', 'surat_keluar.view', 1, NOW(), NOW()),
('admin_surat_keluar', 'surat_keluar.create', 1, NOW(), NOW()),
('admin_surat_keluar', 'surat_keluar.edit', 1, NOW(), NOW()),
('admin_surat_keluar', 'surat_keluar.delete', 1, NOW(), NOW()),
-- Pemimpin Permissions
('pemimpin', 'surat_masuk.view', 1, NOW(), NOW()),
('pemimpin', 'surat_masuk.create', 0, NOW(), NOW()),
('pemimpin', 'surat_masuk.edit', 1, NOW(), NOW()),
('pemimpin', 'surat_masuk.delete', 0, NOW(), NOW()),
('pemimpin', 'surat_keluar.view', 1, NOW(), NOW()),
('pemimpin', 'surat_keluar.create', 0, NOW(), NOW()),
('pemimpin', 'surat_keluar.edit', 0, NOW(), NOW()),
('pemimpin', 'surat_keluar.delete', 0, NOW(), NOW()),
('pemimpin', 'surat_keluar.approve', 1, NOW(), NOW()),
-- Operator Permissions
('operator', 'surat_masuk.view', 1, NOW(), NOW()),
('operator', 'surat_masuk.create', 1, NOW(), NOW()),
('operator', 'surat_masuk.edit', 1, NOW(), NOW()),
('operator', 'surat_masuk.delete', 0, NOW(), NOW()),
('operator', 'surat_keluar.view', 1, NOW(), NOW()),
('operator', 'surat_keluar.create', 1, NOW(), NOW()),
('operator', 'surat_keluar.edit', 1, NOW(), NOW()),
('operator', 'surat_keluar.delete', 0, NOW(), NOW()),
-- Pegawai Permissions
('pegawai', 'surat_masuk.view', 1, NOW(), NOW()),
('pegawai', 'surat_masuk.create', 0, NOW(), NOW()),
('pegawai', 'surat_masuk.edit', 0, NOW(), NOW()),
('pegawai', 'surat_masuk.delete', 0, NOW(), NOW()),
('pegawai', 'surat_keluar.view', 1, NOW(), NOW()),
('pegawai', 'surat_keluar.create', 1, NOW(), NOW()),
('pegawai', 'surat_keluar.edit', 0, NOW(), NOW()),
('pegawai', 'surat_keluar.delete', 0, NOW(), NOW());

-- =====================================================
-- END OF SQL FILE
-- =====================================================
