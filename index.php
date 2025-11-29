<?php
session_start();

// Cek apakah user sudah login?
if (isset($_SESSION['status']) && $_SESSION['status'] == "login") {
    
    // PILIHAN 1: Jika ingin langsung masuk ke Halaman BUKU, pakai ini:
    header("Location: buku.php");
    
    // PILIHAN 2: Jika ingin langsung masuk ke Halaman KATEGORI, ubah jadi:
    // header("Location: kategori.php");
    
} else {
    // Jika belum login, tendang ke halaman Login
    header("Location: login.php");
}
exit;
?>