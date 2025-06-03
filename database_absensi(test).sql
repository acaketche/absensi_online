-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 22 Bulan Mei 2025 pada 08.12
-- Versi server: 8.0.30
-- Versi PHP: 8.2.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `database_absensi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `academic_years`
--

CREATE TABLE `academic_years` (
  `id` int NOT NULL,
  `year_name` varchar(20) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_active` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `academic_years`
--

INSERT INTO `academic_years` (`id`, `year_name`, `start_date`, `end_date`, `is_active`, `created_at`, `updated_at`) VALUES
(9, '2024/2025', '2024-07-08', '2025-06-26', 1, '2025-03-22 13:31:57', '2025-03-29 10:49:12');

-- --------------------------------------------------------

--
-- Struktur dari tabel `attendance_status`
--

CREATE TABLE `attendance_status` (
  `status_id` int NOT NULL,
  `status_name` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `attendance_status`
--

INSERT INTO `attendance_status` (`status_id`, `status_name`, `created_at`, `updated_at`) VALUES
(1, 'Hadir', '2025-03-22 21:10:14', '2025-03-22 21:10:14'),
(2, 'Sakit', '2025-03-22 21:10:14', '2025-03-22 21:10:14'),
(3, 'Izin', '2025-03-22 21:10:14', '2025-03-22 21:10:14'),
(4, 'Alpha', '2025-03-22 21:10:14', '2025-03-22 21:10:14'),
(5, 'Terlambat', '2025-03-22 21:10:14', '2025-03-22 21:10:14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `books`
--

CREATE TABLE `books` (
  `id` int NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(100) DEFAULT NULL,
  `publisher` varchar(100) DEFAULT NULL,
  `year_published` year DEFAULT NULL,
  `stock` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `books`
--

INSERT INTO `books` (`id`, `code`, `title`, `author`, `publisher`, `year_published`, `stock`, `created_at`, `updated_at`) VALUES
(6, 'MAT-2022-A1', 'Matematika Dasar', 'Dr. Siti Aminah', 'Penerbit Cerdas', '2022', 10, '2025-04-06 17:49:11', '2025-04-06 17:49:11'),
(7, 'IND-2021-03', 'Bahasa Indonesia', 'Budi Santoso', 'Gramedia', '2021', 15, '2025-04-06 17:49:11', '2025-04-06 17:49:11'),
(8, 'FIS-2020-X', 'Fisika Kelas X', 'Anton Wijaya', 'Penerbit Ilmu', '2020', 7, '2025-04-06 17:49:11', '2025-04-06 17:49:11'),
(9, 'SEJ-2019-02', 'Sejarah Indonesia', 'Rina Wahyuni', 'Erlangga', '2019', 5, '2025-04-06 17:49:11', '2025-04-06 17:49:11'),
(10, 'BIO-2023-B3', 'Biologi SMA', 'Dr. Ahmad Hidayat', 'Bumi Ilmu', '2023', 20, '2025-04-06 17:49:11', '2025-04-06 17:49:11'),
(11, 'KIM-2021-05', 'Kimia Kelas XII', 'Dr. Rina Hartati', 'Kimia Press', '2021', 8, '2025-04-06 17:49:11', '2025-04-06 17:49:11'),
(12, 'SJ-01', 'Sejarah', 'Putri', 'gramedia', '2025', 2, '2025-05-18 19:08:50', '2025-05-18 19:08:50');

-- --------------------------------------------------------

--
-- Struktur dari tabel `book_loans`
--

CREATE TABLE `book_loans` (
  `id` int NOT NULL,
  `id_student` varchar(20) DEFAULT NULL,
  `book_id` int DEFAULT NULL,
  `loan_date` date NOT NULL,
  `due_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT 'On Loan',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `academic_year_id` int NOT NULL,
  `semester_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `book_loans`
--

INSERT INTO `book_loans` (`id`, `id_student`, `book_id`, `loan_date`, `due_date`, `return_date`, `status`, `created_at`, `updated_at`, `academic_year_id`, `semester_id`) VALUES
(18, '1861', 6, '2025-05-18', '2025-05-30', '2025-05-20', 'Dikembalikan', '2025-05-18 00:29:11', '2025-05-20 00:47:11', 9, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `classes`
--

CREATE TABLE `classes` (
  `class_id` int NOT NULL,
  `class_name` varchar(50) NOT NULL,
  `id_employee` varchar(20) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `academic_year_id` int NOT NULL,
  `semester_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `classes`
--

INSERT INTO `classes` (`class_id`, `class_name`, `id_employee`, `created_at`, `updated_at`, `academic_year_id`, `semester_id`) VALUES
(8, 'X E2', '19851026', '2025-05-17 16:17:05', '2025-05-17 16:17:05', 9, 1),
(9, 'X E3', '19890415', '2025-05-17 16:17:27', '2025-05-17 16:17:27', 9, 1),
(10, 'X E4', '19851026', '2025-05-19 02:06:23', '2025-05-19 02:06:23', 9, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `employees`
--

CREATE TABLE `employees` (
  `id_employee` varchar(20) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `birth_place` varchar(100) NOT NULL,
  `birth_date` date DEFAULT NULL,
  `gender` enum('L','P') NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `role_id` int DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `position_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `employees`
--

INSERT INTO `employees` (`id_employee`, `fullname`, `birth_place`, `birth_date`, `gender`, `phone_number`, `email`, `role_id`, `password`, `photo`, `qr_code`, `created_at`, `updated_at`, `position_id`) VALUES
('19680222', 'Efda Sofliarni, S.Pd. M.Si', 'Payakumbuh', '1968-02-22', 'P', '084327327645', 'Efda@gmail.com', 2, '$2y$12$/V77PfIumewt.GVfljcRv.Vv4cJ2dwvJJRJs6rTtscw5eAWTUyj7W', 'photo_pegawai/O0CFV3dAn0nGURSnXDKILK0HZo89wDX8GTr9vNH2.png', 'qrcode_pegawai/8KBM1AIbpAOzXZeovZtMcfRlQMNpe6zZMj4hnHSO.png', '2025-05-17 08:38:42', '2025-05-17 08:43:06', 1),
('19750925', 'Desvarina, S.Pd', 'Payakumbuh', '1975-09-25', 'P', '082187302982', 'desvarina@gmail.com', 4, '$2y$12$uUSJPrLlWfcfJmWfMLZCIuxy5pPknhqbCCB.4daEGSr.jYqc/hFuu', 'photo_pegawai/siueB9BZrRw8GkrVIpDsqdeM9nQs034AiOHiJfHY.png', 'qrcode_pegawai/TQe3ox4ITqUPCwBwZgnG3ZpE4ENHDk2Fqp0uDezd.png', '2025-05-17 09:12:53', '2025-05-17 17:04:16', 6),
('19760618', 'Joni Hendri, S.Pd, M.Si', 'Payakumbuh', '1976-06-18', 'L', '084327327642', 'Joni@gmail.com', 3, '$2y$12$wBXFnfosz2Ch4rbQNKnToeOHqz7fSzYeL4G3hJoagEjriyymtov7a', 'photo_pegawai/89G4ckCdaqteqy4HsHOyCs2x7pxUj1aBqcobH5aw.png', 'qrcode_pegawai/VSYBboTMEjyc6AIbBwTq24THf5P7CcV4R4Ref067.png', '2025-05-17 09:04:35', '2025-05-17 09:04:35', 3),
('19851026', 'Dania Keumala Dewi, S.Pd', 'Payakumbuh', '1985-10-26', 'P', '082187302982', 'Dania@gmail.com', NULL, '$2y$12$LYNrAaVSgtMB2RTQBXWk1upB7yLmcRzNrrg6zvgpxoViroN.9OE0W', 'photo_pegawai/wov6bI8wgk7Ex3PHwaKiQjBTre2imJ7TAKAmXhVH.png', 'qrcode_pegawai/aWoj42yD0lZD2oCMyNAr7uwr6NBIS9CB24CsacZn.png', '2025-05-17 09:02:31', '2025-05-17 17:04:27', 7),
('19890331', 'Dian Fajrina, S.Pd', 'Payakumbuh', '1989-03-31', 'P', '084327327642', 'dian@gmail.com', 5, '$2y$12$uIZ3ODj4XLOjYOAqruEfJ.Hn8t6ISZf.Hi/4pUc9SjyLAhVL/5pTK', 'photo_pegawai/0cwZAGHkdXwUpDKtOO4HJvWQqevipol4OYlNCTKb.png', 'qrcode_pegawai/4NGFGTkosVOz5HZ1wk2G3JchgONSIeOk48fCO3aY.png', '2025-05-17 09:14:18', '2025-05-17 17:04:42', 4),
('19890415', 'Fandi Pratama, S.Pd', 'Medan', '1989-04-15', 'L', '084327327645', 'fandi@gmail.com', NULL, '$2y$12$qVN2.TPaYWfFTHFwZnDcp.LMCSd6PIuSqpgTS5hotWfTu/a3x7rRC', 'photo_pegawai/0L87ZrxsF5yg0Ljv0JpGuCMp05518NW1aUAiwaQy.png', 'qrcode_pegawai/ua7DHxP1HRHlezg0RhP3GTeVeDklwX2OBERw6ZFa.png', '2025-05-17 09:16:03', '2025-05-17 17:04:54', 7);

-- --------------------------------------------------------

--
-- Struktur dari tabel `employee_attendances`
--

CREATE TABLE `employee_attendances` (
  `id` int NOT NULL,
  `id_employee` varchar(20) DEFAULT NULL,
  `status_id` int DEFAULT NULL,
  `attendance_date` date NOT NULL,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `academic_year_id` int NOT NULL,
  `semester_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `employee_attendances`
--

INSERT INTO `employee_attendances` (`id`, `id_employee`, `status_id`, `attendance_date`, `check_in`, `check_out`, `created_at`, `updated_at`, `academic_year_id`, `semester_id`) VALUES
(1, '19750925', 1, '2025-05-18', '08:09:00', NULL, '2025-05-17 11:04:03', '2025-05-17 11:04:03', 9, 2),
(2, '19680222', 3, '2025-05-18', '07:00:00', NULL, '2025-05-17 17:52:52', '2025-05-17 18:26:33', 9, 2),
(3, '19851026', 3, '2025-05-18', '08:00:00', NULL, '2025-05-17 17:53:28', '2025-05-17 17:53:28', 9, 2),
(4, '19760618', 1, '2025-05-19', '08:00:00', '12:00:00', '2025-05-18 18:14:48', '2025-05-18 18:15:21', 9, 2),
(5, '19851026', 1, '2025-05-19', '08:00:00', NULL, '2025-05-18 19:03:58', '2025-05-18 19:03:58', 9, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `holidays`
--

CREATE TABLE `holidays` (
  `id` int NOT NULL,
  `holiday_date` date NOT NULL,
  `description` text,
  `academic_year_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `holidays`
--

INSERT INTO `holidays` (`id`, `holiday_date`, `description`, `academic_year_id`, `created_at`, `updated_at`) VALUES
(15, '2024-08-17', 'Hari Kemerdekaan Indonesia', 9, '2025-03-22 20:58:46', '2025-03-22 20:59:10'),
(16, '2024-12-25', 'Hari Natal', 9, '2025-03-22 20:58:46', '2025-05-18 10:12:26'),
(17, '2025-01-01', 'Tahun Baru Masehi', NULL, '2025-03-22 20:58:46', '2025-03-22 20:58:46'),
(18, '2025-03-22', 'Hari Raya Nyepi', NULL, '2025-03-22 20:58:46', '2025-03-22 20:58:46');

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `payments`
--

CREATE TABLE `payments` (
  `id` int NOT NULL,
  `id_student` varchar(50) NOT NULL,
  `academic_year_id` int NOT NULL,
  `semester_id` int DEFAULT NULL,
  `id_spp` varchar(30) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '500000.00',
  `status` enum('belum','sebagian','lunas') NOT NULL DEFAULT 'belum',
  `last_update` date DEFAULT NULL,
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `payments`
--

INSERT INTO `payments` (`id`, `id_student`, `academic_year_id`, `semester_id`, `id_spp`, `amount`, `status`, `last_update`, `notes`) VALUES
(9, '1894', 9, 2, '682a926307b1a', 800000.00, 'lunas', '2025-05-19', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `positions`
--

CREATE TABLE `positions` (
  `id` int NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `positions`
--

INSERT INTO `positions` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Kepala Sekolah', '2025-04-16 16:01:53', '2025-04-16 16:01:53'),
(2, 'Wakil Kepala Sekolah', '2025-04-16 16:01:53', '2025-04-16 16:01:53'),
(3, 'Kepala Tata Usaha', '2025-04-16 16:01:53', '2025-04-16 16:01:53'),
(4, 'Kepala Perpustakaan', '2025-04-16 16:01:53', '2025-04-16 16:01:53'),
(5, 'Staff Administrasi', '2025-04-16 16:01:53', '2025-04-16 16:01:53'),
(6, 'Pegawai Piket', '2025-04-16 16:01:53', '2025-04-16 16:01:53'),
(7, 'Wali Kelas\r\n', '2025-04-16 16:01:53', '2025-05-15 05:28:25'),
(8, 'Guru BK (Bimbingan Konseling)', '2025-04-16 16:01:53', '2025-04-16 16:01:53'),
(9, 'Tenaga Kebersihan', '2025-04-16 16:01:53', '2025-04-16 16:01:53'),
(10, 'Security', '2025-04-16 16:01:53', '2025-04-16 16:01:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `rapor`
--

CREATE TABLE `rapor` (
  `id` int NOT NULL,
  `id_student` varchar(50) NOT NULL,
  `class_id` int NOT NULL,
  `academic_year_id` int NOT NULL,
  `semester_id` int NOT NULL,
  `report_date` date NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `description` text,
  `status_report` enum('Belum Ada','Sudah Ada') DEFAULT 'Belum Ada',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `rapor`
--

INSERT INTO `rapor` (`id`, `id_student`, `class_id`, `academic_year_id`, `semester_id`, `report_date`, `file_path`, `description`, `status_report`, `created_at`, `updated_at`) VALUES
(5, '1862', 8, 9, 2, '2025-05-19', 'rapor/Fz8tT9L2NNRH5anvMDVMUPVWHCnIbVBm2nyX2167.pdf', NULL, 'Sudah Ada', '2025-05-18 19:10:27', '2025-05-18 19:10:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `role_name` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `created_at`, `updated_at`) VALUES
(1, 'Wali Kelas', '2025-02-19 07:55:30', '2025-04-16 15:58:36'),
(2, 'Super Admin', '2025-02-19 09:43:15', '2025-04-16 15:50:53'),
(3, 'Admin Tata Usaha', '2025-02-19 09:43:15', '2025-04-16 15:50:53'),
(4, 'Admin Pegawai Piket', '2025-02-19 09:43:15', '2025-04-16 15:50:53'),
(5, 'Admin Perpustakaan', '2025-02-19 09:43:15', '2025-04-16 15:50:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `semesters`
--

CREATE TABLE `semesters` (
  `id` int NOT NULL,
  `academic_year_id` int NOT NULL,
  `semester_name` varchar(50) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `semesters`
--

INSERT INTO `semesters` (`id`, `academic_year_id`, `semester_name`, `start_date`, `end_date`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 9, 'Ganjil', '2024-07-08', '2024-12-20', 0, '2025-03-25 17:10:01', '2025-05-17 09:18:45'),
(2, 9, 'Genap', '2025-01-06', '2025-06-26', 1, '2025-03-25 17:10:01', '2025-05-17 09:18:52');

-- --------------------------------------------------------

--
-- Struktur dari tabel `spp`
--

CREATE TABLE `spp` (
  `id` varchar(30) NOT NULL,
  `academic_year_id` int NOT NULL,
  `semester_id` int DEFAULT NULL,
  `class_id` int DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT '500000.00',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `spp`
--

INSERT INTO `spp` (`id`, `academic_year_id`, `semester_id`, `class_id`, `amount`, `created_at`, `updated_at`) VALUES
('67effbbe7ef83', 9, 1, 1, 800000.00, '2025-04-04 15:33:18', '2025-04-21 04:42:06'),
('68282d8220f85', 9, 1, 3, 800000.00, '2025-05-17 06:32:34', NULL),
('68299bcf4ba67', 9, 2, 8, 800000.00, '2025-05-18 08:35:27', NULL),
('682a926307b1a', 9, 2, 9, 800000.00, '2025-05-19 02:07:31', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `students`
--

CREATE TABLE `students` (
  `id_student` varchar(20) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `class_id` int DEFAULT NULL,
  `parent_phonecell` varchar(50) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `qrcode` varchar(255) DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `birth_place` varchar(255) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `gender` enum('L','P') NOT NULL,
  `academic_year_id` int NOT NULL,
  `semester_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `students`
--

INSERT INTO `students` (`id_student`, `fullname`, `class_id`, `parent_phonecell`, `photo`, `qrcode`, `password`, `created_at`, `updated_at`, `birth_place`, `birth_date`, `gender`, `academic_year_id`, `semester_id`) VALUES
('001', 'Putri Adellia', 8, '08218213798', 'photo_siswa/PLxtW4v632dOKJIN4Y9zIhIqeQ1AAcr6Kqlx1cr8.png', NULL, '$2y$12$VzQ528.4SdkxqwvjWs6WAeR5K5URXJpTKeov3zgTsLN4QuJnv8uXu', '2025-05-18 18:58:29', '2025-05-18 18:58:29', 'MEDAN', '2003-04-10', 'P', 9, 2),
('1861', 'Ahmad Hasudungan Siregar', 8, 'Ahmad', NULL, NULL, '$2y$12$Rs3ToBNzsRn1q285I0ZMauQ3RbGMbS/WWihmSypN85Kx3yPhl6FkS', '2025-05-17 19:29:33', '2025-05-17 19:29:33', 'Payakumbuh', '2008-07-25', 'L', 9, 2),
('1862', 'AHMAD HAYKAL ONSU', 8, '0087504698', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Jakarta', '2006-07-25', 'L', 9, 2),
('1863', 'ALSA SYAHRANI', 8, '0087398532', 'photo_siswa/cCV2s2v8XD95HWMRSIXRzs3b9YzYmpdXnCGDpxDP.png', NULL, 'password123', '2024-05-18 03:00:00', '2025-05-18 11:18:07', 'Bandung', '2007-05-10', 'P', 9, 2),
('1864', 'Amel Marsa Putri', 8, '0093384493', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Surabaya', '2006-11-30', 'P', 9, 2),
('1865', 'Andriano', 8, '0091437335', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Medan', '2006-02-17', 'L', 9, 2),
('1866', 'BIRU OKTARI RANDANI', 8, '0084197562', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Bandung', '2007-06-04', 'P', 9, 2),
('1867', 'CAHAYA ANNISA', 8, '0082499867', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Jakarta', '2006-08-12', 'P', 9, 2),
('1868', 'CANTIKA ARUMI SARI', 8, '0096293421', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Surabaya', '2007-01-23', 'P', 9, 2),
('1869', 'FANZA JANIKA', 8, '0094660639', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Medan', '2006-04-11', 'L', 9, 2),
('1870', 'Fatthan Akbar Jovi', 8, '0099620913', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Jakarta', '2006-12-05', 'L', 9, 2),
('1871', 'GELSIA PRIMADONA', 8, '0083524653', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Bandung', '2007-07-15', 'P', 9, 2),
('1872', 'KESIA RAMADHANI', 8, '3080941475', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Jakarta', '2006-10-20', 'P', 9, 2),
('1873', 'Keysha Zahwa Amelia', 8, '0107742137', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Surabaya', '2007-03-14', 'P', 9, 2),
('1874', 'LAILATUSHOLEHA', 8, '0086127234', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Jakarta', '2006-05-27', 'P', 9, 2),
('1875', 'Lailatul Rahmi', 8, '0066494940', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Bandung', '2007-09-09', 'P', 9, 2),
('1876', 'Muhammad Vikriansyah', 8, '0079902953', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Jakarta', '2006-04-28', 'L', 9, 2),
('1877', 'Muhammad Aulia Asidiqi', 8, '0082607212', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Medan', '2006-08-30', 'L', 9, 2),
('1878', 'MUHAMMAD FIRZAN', 8, '0074378038', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Jakarta', '2006-11-11', 'L', 9, 2),
('1879', 'MUHAMMAD MEIDI REZKI', 8, '0092128442', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Bandung', '2007-01-17', 'L', 9, 2),
('1880', 'MUHAMMAD RAIHAN', 8, '0055560337', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Surabaya', '2006-09-21', 'L', 9, 2),
('1881', 'MUHAMMAD SABIL', 8, '0073893214', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Medan', '2006-06-05', 'L', 9, 2),
('1882', 'Naysa Azahra', 8, '0096079370', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Surabaya', '2007-08-02', 'P', 9, 2),
('1883', 'Nasya Febriani', 8, '0098660812', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Bandung', '2007-07-29', 'P', 9, 2),
('1884', 'RAKA JUMEYDY', 8, '0088160846', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Medan', '2006-12-14', 'L', 9, 2),
('1885', 'REYMON WAHYUDI', 8, '0089058799', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Jakarta', '2006-07-03', 'L', 9, 2),
('1886', 'Rifkye Anugrah Pratama', 8, '0085376996', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Bandung', '2007-01-08', 'L', 9, 2),
('1887', 'SADIRA NAHIYA VIANKA', 8, '0085553055', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Surabaya', '2007-06-15', 'P', 9, 2),
('1888', 'SALWA RAUDHA AKORI', 8, '0092686341', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Medan', '2007-02-19', 'P', 9, 2),
('1889', 'SANTIA BELA', 8, '0108970168', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Jakarta', '2007-08-21', 'P', 9, 2),
('1890', 'Shofiyyah Ufairah', 8, '0091251638', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Bandung', '2007-04-11', 'P', 9, 2),
('1891', 'SRI WAHYUNI GUSMALIA', 8, '0085017921', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Surabaya', '2007-03-23', 'P', 9, 2),
('1892', 'SYAIFUL ALGHIFARI', 8, '0063032050', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Medan', '2006-11-07', 'L', 9, 2),
('1893', 'Sylfa Ashila', 8, '0097124494', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Jakarta', '2007-02-04', 'P', 9, 2),
('1894', 'AINA KASIH', 9, '0086451187', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Bandung', '2007-06-18', 'P', 9, 2),
('1895', 'ALIFYA DESMAYANTI VIDMA', 9, '0086864797', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Surabaya', '2006-10-29', 'P', 9, 2),
('1896', 'ANGGINA ITO ANSYARY NASUTION', 9, '0096600988', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Medan', '2007-01-12', 'P', 9, 2),
('1897', 'AZZURA FAIZATUL MAHYA', 9, '0091528011', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Jakarta', '2007-05-05', 'P', 9, 2),
('1898', 'DANNY ABDURROHMAN', 9, '0095260396', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Bandung', '2006-08-18', 'L', 9, 2),
('1899', 'Dinda Aulia Putri', 9, '0072726976', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Surabaya', '2007-11-02', 'P', 9, 2),
('1900', 'EKATAMA AZKA', 9, '0076703950', NULL, NULL, 'password123', '2024-05-18 03:00:00', '2024-05-18 03:00:00', 'Medan', '2006-04-14', 'L', 9, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `student_attendances`
--

CREATE TABLE `student_attendances` (
  `id` int NOT NULL,
  `id_student` varchar(20) DEFAULT NULL,
  `class_id` int DEFAULT NULL,
  `attendance_date` date NOT NULL,
  `attendance_time` time NOT NULL,
  `check_in_time` time DEFAULT NULL,
  `check_out_time` time DEFAULT NULL,
  `status_id` int DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `academic_year_id` int NOT NULL,
  `semester_id` int NOT NULL,
  `document` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `academic_years`
--
ALTER TABLE `academic_years`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `attendance_status`
--
ALTER TABLE `attendance_status`
  ADD PRIMARY KEY (`status_id`);

--
-- Indeks untuk tabel `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `book_loans`
--
ALTER TABLE `book_loans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_student` (`id_student`),
  ADD KEY `book_id` (`book_id`),
  ADD KEY `academic_year_id` (`academic_year_id`),
  ADD KEY `semester_id` (`semester_id`);

--
-- Indeks untuk tabel `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`class_id`),
  ADD KEY `id_employee` (`id_employee`),
  ADD KEY `academic_year_id` (`academic_year_id`),
  ADD KEY `semester_id` (`semester_id`);

--
-- Indeks untuk tabel `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id_employee`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `position_id` (`position_id`);

--
-- Indeks untuk tabel `employee_attendances`
--
ALTER TABLE `employee_attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_employee` (`id_employee`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `academic_year_id` (`academic_year_id`),
  ADD KEY `semester_id` (`semester_id`);

--
-- Indeks untuk tabel `holidays`
--
ALTER TABLE `holidays`
  ADD PRIMARY KEY (`id`),
  ADD KEY `academic_year_id` (`academic_year_id`);

--
-- Indeks untuk tabel `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `fk_password_resets_email` (`email`);

--
-- Indeks untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_student` (`id_student`),
  ADD KEY `academic_year_id` (`academic_year_id`);

--
-- Indeks untuk tabel `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `rapor`
--
ALTER TABLE `rapor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_rapor` (`id_student`,`academic_year_id`,`semester_id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `academic_year_id` (`academic_year_id`),
  ADD KEY `semester_id` (`semester_id`);

--
-- Indeks untuk tabel `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `semesters`
--
ALTER TABLE `semesters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_semesters_academic_year` (`academic_year_id`);

--
-- Indeks untuk tabel `spp`
--
ALTER TABLE `spp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `academic_year_id` (`academic_year_id`);

--
-- Indeks untuk tabel `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id_student`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `fk_students_academic_year` (`academic_year_id`),
  ADD KEY `fk_students_semester` (`semester_id`);

--
-- Indeks untuk tabel `student_attendances`
--
ALTER TABLE `student_attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_student` (`id_student`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `academic_year_id` (`academic_year_id`),
  ADD KEY `semester_id` (`semester_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `academic_years`
--
ALTER TABLE `academic_years`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `attendance_status`
--
ALTER TABLE `attendance_status`
  MODIFY `status_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `books`
--
ALTER TABLE `books`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `book_loans`
--
ALTER TABLE `book_loans`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `classes`
--
ALTER TABLE `classes`
  MODIFY `class_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `employee_attendances`
--
ALTER TABLE `employee_attendances`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `holidays`
--
ALTER TABLE `holidays`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `positions`
--
ALTER TABLE `positions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `rapor`
--
ALTER TABLE `rapor`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `semesters`
--
ALTER TABLE `semesters`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `student_attendances`
--
ALTER TABLE `student_attendances`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `book_loans`
--
ALTER TABLE `book_loans`
  ADD CONSTRAINT `book_loans_ibfk_1` FOREIGN KEY (`id_student`) REFERENCES `students` (`id_student`) ON DELETE CASCADE,
  ADD CONSTRAINT `book_loans_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `book_loans_ibfk_3` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`),
  ADD CONSTRAINT `book_loans_ibfk_4` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`);

--
-- Ketidakleluasaan untuk tabel `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`id_employee`) REFERENCES `employees` (`id_employee`) ON DELETE SET NULL,
  ADD CONSTRAINT `classes_ibfk_2` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`),
  ADD CONSTRAINT `classes_ibfk_3` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`);

--
-- Ketidakleluasaan untuk tabel `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `employees_ibfk_2` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `employee_attendances`
--
ALTER TABLE `employee_attendances`
  ADD CONSTRAINT `employee_attendances_ibfk_1` FOREIGN KEY (`id_employee`) REFERENCES `employees` (`id_employee`) ON DELETE CASCADE,
  ADD CONSTRAINT `employee_attendances_ibfk_2` FOREIGN KEY (`status_id`) REFERENCES `attendance_status` (`status_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employee_attendances_ibfk_3` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`),
  ADD CONSTRAINT `employee_attendances_ibfk_4` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`);

--
-- Ketidakleluasaan untuk tabel `holidays`
--
ALTER TABLE `holidays`
  ADD CONSTRAINT `holidays_ibfk_1` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `fk_password_resets_email` FOREIGN KEY (`email`) REFERENCES `employees` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`id_student`) REFERENCES `students` (`id_student`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`);

--
-- Ketidakleluasaan untuk tabel `rapor`
--
ALTER TABLE `rapor`
  ADD CONSTRAINT `rapor_ibfk_1` FOREIGN KEY (`id_student`) REFERENCES `students` (`id_student`) ON DELETE CASCADE,
  ADD CONSTRAINT `rapor_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rapor_ibfk_3` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rapor_ibfk_4` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `semesters`
--
ALTER TABLE `semesters`
  ADD CONSTRAINT `fk_semesters_academic_year` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `semesters_ibfk_1` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `fk_students_academic_year` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_students_semester` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `student_attendances`
--
ALTER TABLE `student_attendances`
  ADD CONSTRAINT `student_attendances_ibfk_1` FOREIGN KEY (`id_student`) REFERENCES `students` (`id_student`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_attendances_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_attendances_ibfk_4` FOREIGN KEY (`status_id`) REFERENCES `attendance_status` (`status_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_attendances_ibfk_5` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`),
  ADD CONSTRAINT `student_attendances_ibfk_6` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
