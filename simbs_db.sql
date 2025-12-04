-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 04 Des 2025 pada 04.54
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simbs_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `buku`
--

CREATE TABLE `buku` (
  `id_buku` int(11) NOT NULL,
  `judul` varchar(200) NOT NULL,
  `id_kategori` varchar(255) DEFAULT NULL,
  `tanggal_input` datetime DEFAULT current_timestamp(),
  `penulis` varchar(100) DEFAULT NULL,
  `penerbit` varchar(100) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `tahun_terbit` int(11) DEFAULT NULL,
  `stok` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `buku`
--

INSERT INTO `buku` (`id_buku`, `judul`, `id_kategori`, `tanggal_input`, `penulis`, `penerbit`, `gambar`, `tahun_terbit`, `stok`) VALUES
(10011, 'Danur', '6,5,1', '2025-11-29 08:42:47', 'Risa Saraswati', 'Bukune', '804804935_danur.jpg', 2011, 40),
(10012, 'Dilan 1990', '9,1,7', '2025-11-29 08:46:51', 'Pidi Baiq', 'Pastel Books', '756449178_dilan.jpg', 2014, 25),
(10013, 'Marmut merah jambu', '8,1,7', '2025-11-29 08:52:26', 'Raditya Dika', 'Bukune', '1595507501_Marmut_Merah_Jambu.jpg', 2010, 32),
(10014, 'Sherlock Holmes: Koleksi Kasus 1 ', '11,5,1,10', '2025-11-29 08:57:23', 'Sir Arthur Conan Doyle', 'Gramedia Pustaka Utama', '2049182636_9786020312910_Sherlock-Holmes_Koleksi-Kasus-1.jpg', 2015, 18);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `tanggal_input` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `tanggal_input`) VALUES
(1, 'Novel', '2025-11-27 10:02:29'),
(2, 'Komik', '2025-11-27 10:02:29'),
(3, 'Buku Pelajaran', '2025-11-27 10:02:29'),
(4, 'Ensiklopedia', '2025-11-27 10:02:29'),
(5, 'Misteri', '2025-11-27 10:24:31'),
(6, 'Horror', '2025-11-27 10:25:20'),
(7, 'Romance', '2025-11-29 08:45:21'),
(8, 'Komedi', '2025-11-29 08:50:50'),
(9, 'Drama', '2025-11-29 08:52:46'),
(10, 'Thriller', '2025-11-29 08:57:49'),
(11, 'Fiksi', '2025-11-29 08:57:57'),
(12, 'Non Fiksi', '2025-11-29 08:58:07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`) VALUES
(1, 'nadia', 'nadiaaiyanadia@gmail.com', 'cheesecake666');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `buku`
--
ALTER TABLE `buku`
  MODIFY `id_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10015;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
