-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 05 Des 2024 pada 18.42
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `absensi_magangbps`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_absensi`
--

CREATE TABLE `tbl_absensi` (
  `id_absensi` int(15) NOT NULL,
  `id_mahasiswa` int(15) NOT NULL,
  `status` enum('Hadir','Tidak Hadir','Izin','Sakit') NOT NULL DEFAULT 'Tidak Hadir',
  `waktu` time NOT NULL,
  `tanggal` date NOT NULL,
  `latitude` varchar(50) NOT NULL,
  `longitude` varchar(50) NOT NULL,
  `foto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_absensi`
--

INSERT INTO `tbl_absensi` (`id_absensi`, `id_mahasiswa`, `status`, `waktu`, `tanggal`, `latitude`, `longitude`, `foto`) VALUES
(38, 2, 'Hadir', '12:00:53', '2024-09-25', '', '', ''),
(40, 2, 'Hadir', '01:46:28', '2024-11-29', '-2.221584', '113.931082', 'foto_6748ba84e76d9.png'),
(41, 2, 'Hadir', '01:52:17', '2024-11-29', '-2.221584', '113.931082', 'foto_6748bbe1b01d8.png'),
(42, 2, 'Hadir', '01:53:32', '2024-11-29', '-2.22161875', '113.9310595', 'foto_6748bc2c9bb5e.png'),
(43, 2, 'Hadir', '12:20:00', '2024-11-30', '-2.2116231916424347', '113.92664459984618', ''),
(48, 2, 'Izin', '00:07:10', '2024-12-06', '-2.22161875', '113.9310595', 'foto_6751ddbe3aab9.png'),
(54, 2, 'Izin', '00:25:00', '2024-12-06', '-2.221584', '113.931082', 'foto_6751e1ec004d6.png'),
(55, 2, 'Izin', '00:26:14', '2024-12-06', '-2.221584', '113.931082', 'foto_6751e23681f9c.png'),
(56, 2, 'Izin', '00:27:45', '2024-12-06', '-2.221584', '113.931082', 'foto_6751e291d8930.png'),
(57, 2, 'Sakit', '00:36:33', '2024-12-06', '-2.221584', '113.931082', 'foto_6751e4a19804e.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `id_admin` int(15) NOT NULL,
  `kode_admin` varchar(4) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `nip` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_admin`
--

INSERT INTO `tbl_admin` (`id_admin`, `kode_admin`, `nama`, `nip`, `email`) VALUES
(1, 'A001', 'Badan Pusat Statistik', '2022122501', 'bps6200@bps.go.id'),
(2, 'A002', 'Ari Kristanto', '2022122502', 'arikristanto@gmail.com');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_alasan`
--

CREATE TABLE `tbl_alasan` (
  `id_alasan` int(15) NOT NULL,
  `id_mahasiswa` int(15) DEFAULT NULL,
  `alasan` varchar(255) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `file_surat` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_alasan`
--

INSERT INTO `tbl_alasan` (`id_alasan`, `id_mahasiswa`, `alasan`, `tanggal`, `file_surat`) VALUES
(22, 2, 'kk', '2024-12-06', 'SURAT PERNYATAAN TIDAK BERTATO & TIDAK BERTINDIK.pdf'),
(23, 2, 'mari', '2024-12-06', 'Desain tanpa judul.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_kegiatan`
--

CREATE TABLE `tbl_kegiatan` (
  `id_kegiatan` int(15) NOT NULL,
  `id_mahasiswa` int(15) DEFAULT NULL,
  `kegiatan1` varchar(255) DEFAULT NULL,
  `waktu_awal` time DEFAULT NULL,
  `waktu_akhir` time DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `file_upload` varchar(200) DEFAULT NULL,
  `kegiatan2` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_kegiatan`
--

INSERT INTO `tbl_kegiatan` (`id_kegiatan`, `id_mahasiswa`, `kegiatan1`, `waktu_awal`, `waktu_akhir`, `tanggal`, `file_upload`, `kegiatan2`) VALUES
(159, 2, 'lll', '11:18:00', '11:23:00', '2024-09-23', '66f0ec2a2543d.pdf', 'makan cuy'),
(162, 2, 'Memasukkan berita', '23:50:00', '12:50:00', '2024-11-30', 'kegiatan_6749f0c56717d5.26316761.jpeg', NULL),
(164, 2, 'Memasukkan berita', '01:05:00', '00:05:00', '2024-11-30', '6749f4645446d.jpeg', 'u9yu'),
(165, 2, 'Menyalakan komputer layanan pagi', '05:07:00', '02:08:00', '2024-11-30', '6749f4fd1db01.jpeg', 'efef'),
(166, 1, 'Merancang sebuah website', '13:30:00', '15:30:00', '2024-11-15', 'kegiatan_674ab109d73180.70764223.jpeg', 'putri');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_koordinat`
--

CREATE TABLE `tbl_koordinat` (
  `id_koordinat` int(11) NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `radius` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_koordinat`
--

INSERT INTO `tbl_koordinat` (`id_koordinat`, `latitude`, `longitude`, `radius`) VALUES
(1, -2.211173200369026, 113.90129619619013, 10000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_mahasiswa`
--

CREATE TABLE `tbl_mahasiswa` (
  `id_mahasiswa` int(15) NOT NULL,
  `kode_mahasiswa` varchar(4) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `universitas` varchar(255) DEFAULT NULL,
  `jurusan` varchar(255) DEFAULT NULL,
  `nim` varchar(255) DEFAULT NULL,
  `mulai_magang` date DEFAULT NULL,
  `akhir_magang` date DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `no_telp` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_mahasiswa`
--

INSERT INTO `tbl_mahasiswa` (`id_mahasiswa`, `kode_mahasiswa`, `nama`, `universitas`, `jurusan`, `nim`, `mulai_magang`, `akhir_magang`, `alamat`, `no_telp`, `foto`) VALUES
(1, 'M001', 'Ade Fitria Putri', 'Muhammadiyah Palangkaraya', 'ilmu Komputerfefef', '062030efdef70163555666', '2024-07-23', '2024-09-23', 'jl. bfefefef', '080808efefefe', 'FEED INFOGRAFIS TABE STIMULUS BANTUAN USAHA.png'),
(2, 'M002', 'Annita Nurhalizawedw', 'Muhammadiyah Palangkaraya', 'ilmu Komputerdwwdw', '0604065601030002dwdw', '2024-11-24', '2024-12-30', 'Jl. G Obos 7awdwdw', '08123456789wdwd0', 'foto_default.png'),
(4, 'M003', 'Lisa Ananda Rizky', 'Muhammadiyah Palangkaraya', 'ilmu Komputer', '0620307016355561', '2024-07-23', '2024-09-23', 'jl. A', '081234567890', 'foto_default.png'),
(5, 'M005', 'Cindy Kurnia Fitriyanti', 'Muhammadiyah Palangkaraya', 'ilmu Komputer', '06203070163555633', '2024-07-23', '2024-09-23', 'jl. Jogja', '081234567890', 'foto_default.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_setting_absensi`
--

CREATE TABLE `tbl_setting_absensi` (
  `id_waktu` int(15) DEFAULT NULL,
  `mulai_absen` time DEFAULT NULL,
  `akhir_absen` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_setting_absensi`
--

INSERT INTO `tbl_setting_absensi` (`id_waktu`, `mulai_absen`, `akhir_absen`) VALUES
(1, '00:01:00', '23:05:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_site`
--

CREATE TABLE `tbl_site` (
  `id_site` int(15) DEFAULT NULL,
  `nama_instansi` varchar(255) DEFAULT NULL,
  `pimpinan` varchar(255) DEFAULT NULL,
  `pembimbing` varchar(255) DEFAULT NULL,
  `no_telp` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_site`
--

INSERT INTO `tbl_site` (`id_site`, `nama_instansi`, `pimpinan`, `pembimbing`, `no_telp`, `alamat`, `website`, `logo`) VALUES
(1, 'Badan Pusat Statistik Prov Kalimantan Tengah', 'Agnes Widiastuti', 'Ari Kristanto', '(0536)3228105', 'Jalan Kapten Piere Tendean No 6', 'http://kalteng.bps.go.id', 'logobps.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id_user` int(15) NOT NULL,
  `kode_pengguna` varchar(4) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `level` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `tbl_user`
--

INSERT INTO `tbl_user` (`id_user`, `kode_pengguna`, `username`, `password`, `level`) VALUES
(1, 'A001', 'bps', '527bf0924e132078a4658cd7b8cd2503', 'Admin'),
(2, 'A002', 'amin', 'e10adc3949ba59abbe56e057f20f883e', 'Admin'),
(3, 'M001', 'ade', 'fa6a6bd136dec26a1dd5e326b7e43254', 'Mahasiswa'),
(4, 'M002', 'annita', '5307ef95e454212b60ea1fe47934716b', 'Mahasiswa'),
(5, 'M003', 'Lisa', 'e9803a706f81a40884b8aeafafb2cfd3', 'Mahasiswa'),
(6, 'a1', 'Annita Nurhaliza', 'e64b78fc3bc91bcbc7dc232ba8ec59e0', 'Admin'),
(8, 'M005', 'Cindy', '17103f51fe7ec5b170c9dd9a8e496b6b', 'Mahasiswa');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tbl_absensi`
--
ALTER TABLE `tbl_absensi`
  ADD PRIMARY KEY (`id_absensi`),
  ADD KEY `idx_mahasiswa` (`id_mahasiswa`);

--
-- Indeks untuk tabel `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`id_admin`),
  ADD KEY `kode_admin` (`kode_admin`);

--
-- Indeks untuk tabel `tbl_alasan`
--
ALTER TABLE `tbl_alasan`
  ADD PRIMARY KEY (`id_alasan`);

--
-- Indeks untuk tabel `tbl_kegiatan`
--
ALTER TABLE `tbl_kegiatan`
  ADD PRIMARY KEY (`id_kegiatan`),
  ADD KEY `tbl_kegiatan_ibfk1_1` (`id_mahasiswa`);

--
-- Indeks untuk tabel `tbl_koordinat`
--
ALTER TABLE `tbl_koordinat`
  ADD PRIMARY KEY (`id_koordinat`);

--
-- Indeks untuk tabel `tbl_mahasiswa`
--
ALTER TABLE `tbl_mahasiswa`
  ADD PRIMARY KEY (`id_mahasiswa`),
  ADD KEY `kode_mahasiswa` (`kode_mahasiswa`);

--
-- Indeks untuk tabel `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `kode_pengguna` (`kode_pengguna`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tbl_absensi`
--
ALTER TABLE `tbl_absensi`
  MODIFY `id_absensi` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT untuk tabel `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `id_admin` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `tbl_alasan`
--
ALTER TABLE `tbl_alasan`
  MODIFY `id_alasan` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `tbl_kegiatan`
--
ALTER TABLE `tbl_kegiatan`
  MODIFY `id_kegiatan` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;

--
-- AUTO_INCREMENT untuk tabel `tbl_koordinat`
--
ALTER TABLE `tbl_koordinat`
  MODIFY `id_koordinat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `tbl_mahasiswa`
--
ALTER TABLE `tbl_mahasiswa`
  MODIFY `id_mahasiswa` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id_user` int(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD CONSTRAINT `tbl_admin_ibfk_1` FOREIGN KEY (`kode_admin`) REFERENCES `tbl_user` (`kode_pengguna`);

--
-- Ketidakleluasaan untuk tabel `tbl_alasan`
--
ALTER TABLE `tbl_alasan`
  ADD CONSTRAINT `tbl_alasan_ibfk1_1` FOREIGN KEY (`id_mahasiswa`) REFERENCES `tbl_mahasiswa` (`id_mahasiswa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tbl_kegiatan`
--
ALTER TABLE `tbl_kegiatan`
  ADD CONSTRAINT `tbl_kegiatan_ibfk1_1` FOREIGN KEY (`id_mahasiswa`) REFERENCES `tbl_mahasiswa` (`id_mahasiswa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tbl_mahasiswa`
--
ALTER TABLE `tbl_mahasiswa`
  ADD CONSTRAINT `tbl_mahasiswa_ibfk_1` FOREIGN KEY (`kode_mahasiswa`) REFERENCES `tbl_user` (`kode_pengguna`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
