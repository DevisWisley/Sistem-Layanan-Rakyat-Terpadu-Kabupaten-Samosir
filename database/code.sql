-- --------------------------------------------------------
-- DATABASE: silara
-- --------------------------------------------------------

CREATE DATABASE IF NOT EXISTS `silara` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `silara`;

-- --------------------------------------------------------
-- TABLE: admin
-- --------------------------------------------------------
CREATE TABLE `admin` (
  `id_admin` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_admin` VARCHAR(100) NOT NULL,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `jabatan` VARCHAR(100),
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin','penduduk','layanan') NOT NULL DEFAULT 'admin',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Password sebaiknya di-hash via PHP
INSERT INTO `admin` (`nama_admin`, `username`, `jabatan`, `password`, `role`) VALUES
('Administrator', 'admin', 'Super Admin', 'admin123', 'admin'),
('Layanan', 'layanan', 'Petugas Layanan', 'layanan123', 'layanan'),
('Penduduk', 'penduduk', 'Warga', 'penduduk123', 'penduduk');

-- --------------------------------------------------------
-- TABLE: penduduk
-- --------------------------------------------------------
CREATE TABLE `penduduk` (
  `nik` VARCHAR(20) PRIMARY KEY,
  `nama_pemohon` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100),
  `tanggal_lahir` DATE,
  `no_telpon` VARCHAR(20),
  `desa` VARCHAR(100),
  `kecamatan` VARCHAR(100),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO `penduduk` (`nik`, `nama_pemohon`, `email`, `tanggal_lahir`, `no_telpon`, `desa`, `kecamatan`) VALUES
('1205010101000003', 'Ahmad Fauzi', 'ahmad@example.com', '1992-03-15', '081234567891', 'Simanindo', 'Pangururan'),
('1205010101000004', 'Rina Oktavia', 'rina@example.com', '1994-06-20', '081234567892', 'Tomok', 'Nainggolan'),
('1205010101000005', 'Agus Santoso', 'agus@example.com', '1988-11-05', '081234567893', 'Simanindo', 'Pangururan'),
('1205010101000006', 'Dewi Lestari', 'dewi@example.com', '1991-09-10', '081234567894', 'Tomok', 'Nainggolan'),
('1205010101000007', 'Bambang Irawan', 'bambang@example.com', '1985-07-25', '081234567895', 'Simanindo', 'Pangururan'),
('1205010101000008', 'Sulastri', 'sulastri@example.com', '1993-01-12', '081234567896', 'Tomok', 'Nainggolan'),
('1205010101000009', 'Hendra Saputra', 'hendra@example.com', '1990-12-30', '081234567897', 'Simanindo', 'Pangururan'),
('1205010101000010', 'Maya Putri', 'maya@example.com', '1996-04-18', '081234567898', 'Tomok', 'Nainggolan'),
('1205010101000011', 'Rudy Hartono', 'rudy@example.com', '1989-08-22', '081234567899', 'Simanindo', 'Pangururan'),
('1205010101000012', 'Lina Marlina', 'lina@example.com', '1992-10-05', '081234567800', 'Tomok', 'Nainggolan');

-- --------------------------------------------------------
-- TABLE: layanan
-- --------------------------------------------------------
CREATE TABLE `layanan` (
  `id_layanan` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_layanan` VARCHAR(150) NOT NULL,
  `kategori` VARCHAR(100),
  `deskripsi` TEXT,
  `syarat_dokumen` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tambahkan data layanan sebelum pengajuan
INSERT INTO `layanan` (`nama_layanan`, `kategori`, `deskripsi`, `syarat_dokumen`) VALUES
('Pembuatan Akta Kelahiran', 'Administrasi Kependudukan', 'Layanan pembuatan akta kelahiran baru.', 'Fotokopi KK, Surat Keterangan Lahir'),
('Pembuatan Surat Keterangan Domisili', 'Administrasi Kependudukan', 'Layanan pembuatan surat domisili.', 'KK, KTP, Surat RT/RW'),
('Pembuatan Kartu Keluarga Baru', 'Administrasi Kependudukan', 'Pembuatan KK baru.', 'Surat Pengantar RT/RW, Foto Copy KTP'),
('Perubahan Data KK', 'Administrasi Kependudukan', 'Perubahan data anggota keluarga.', 'KK Lama, KTP Anggota Keluarga'),
('Pembuatan KTP Elektronik', 'Administrasi Kependudukan', 'KTP baru untuk warga yang sudah berusia 17 tahun.', 'KK, Akta Lahir, Surat Pengantar RT/RW'),
('Pembuatan SIM', 'Kependudukan & Transportasi', 'Surat Izin Mengemudi baru.', 'KTP, Foto, Surat Keterangan Sehat'),
('Perpanjangan SIM', 'Kependudukan & Transportasi', 'Perpanjangan SIM yang habis masa berlaku.', 'SIM Lama, KTP, Foto'),
('Surat Izin Usaha Mikro', 'Perizinan', 'Pembuatan izin usaha mikro baru.', 'KTP, KK, Proposal Usaha'),
('Pembuatan Paspor', 'Imigrasi', 'Pembuatan paspor untuk perjalanan luar negeri.', 'KTP, KK, Akta Lahir, Foto'),
('Pembuatan SKCK', 'Kepolisian', 'Surat Keterangan Catatan Kepolisian.', 'KTP, KK, Surat Pengantar RT/RW');

-- --------------------------------------------------------
-- TABLE: pengajuan
-- --------------------------------------------------------
CREATE TABLE `pengajuan` (
  `id_pengajuan` INT AUTO_INCREMENT PRIMARY KEY,
  `nik` VARCHAR(20) NOT NULL,
  `id_layanan` INT NOT NULL,
  `tanggal_pengajuan` DATE DEFAULT CURRENT_DATE,
  `status` ENUM('Menunggu','Diproses','Selesai','Ditolak') DEFAULT 'Menunggu',
  `keterangan` TEXT,
  `file_pendukung` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`nik`) REFERENCES `penduduk`(`nik`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`id_layanan`) REFERENCES `layanan`(`id_layanan`) ON DELETE CASCADE ON UPDATE CASCADE,
  INDEX (`nik`),
  INDEX (`id_layanan`)
);

-- Insert data pengajuan (pastikan id_layanan valid)
INSERT INTO `pengajuan` (`nik`, `id_layanan`, `tanggal_pengajuan`, `status`, `keterangan`, `file_pendukung`) VALUES
('1205010101000003', 1, '2025-03-01', 'Menunggu', 'Pengajuan akta kelahiran anak pertama', 'akta_ahmad.pdf'),
('1205010101000004', 2, '2025-03-02', 'Diproses', 'Pengajuan surat domisili untuk pindah alamat', 'domisili_rina.pdf'),
('1205010101000005', 3, '2025-03-03', 'Selesai', 'KK baru untuk keluarga baru', 'kk_agus.pdf'),
('1205010101000006', 4, '2025-03-04', 'Ditolak', 'Perubahan data KK tidak lengkap', 'ubah_kk_dewi.pdf'),
('1205010101000007', 5, '2025-03-05', 'Menunggu', 'KTP elektronik baru', 'ktp_bambang.pdf'),
('1205010101000008', 6, '2025-03-06', 'Diproses', 'Pengajuan SIM baru', 'sim_sulastri.pdf'),
('1205010101000009', 7, '2025-03-07', 'Selesai', 'Perpanjangan SIM', 'perpanjang_sim_hendra.pdf'),
('1205010101000010', 8, '2025-03-08', 'Menunggu', 'Izin usaha mikro baru', 'ium_maya.pdf'),
('1205010101000011', 9, '2025-03-09', 'Diproses', 'Pengajuan paspor', 'paspor_rudy.pdf'),
('1205010101000012', 10, '2025-03-10', 'Selesai', 'Pengajuan SKCK untuk melamar kerja', 'skck_lina.pdf');

-- --------------------------------------------------------
-- VIEW: v_pengajuan_detail
-- --------------------------------------------------------
CREATE OR REPLACE VIEW `v_pengajuan_detail` AS
SELECT 
  p.id_pengajuan,
  pd.nik,
  pd.nama_pemohon,
  l.nama_layanan,
  p.tanggal_pengajuan,
  p.status,
  p.keterangan
FROM pengajuan p
JOIN penduduk pd ON p.nik = pd.nik
JOIN layanan l ON p.id_layanan = l.id_layanan;