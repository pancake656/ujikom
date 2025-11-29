<?php
session_start();
include 'koneksi.php';

// Cek Login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit;
}

// Proses Simpan Data
if (isset($_POST['simpan'])) {
    $nama_kategori = $_POST['nama_kategori'];

    // Insert data ke database
    $insert = mysqli_query($koneksi, "INSERT INTO kategori (nama_kategori) VALUES ('$nama_kategori')");
    
    if ($insert) {
        // Jika berhasil, kembali ke halaman kategori
        echo "<script>alert('Berhasil menambah kategori!'); window.location='kategori.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori - SIMBS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #343a40; color: white; }
        .sidebar a { color: #cfd8dc; text-decoration: none; padding: 10px 15px; display: block; }
        .sidebar a:hover, .sidebar a.active { background-color: #495057; color: white; border-left: 4px solid #0d6efd; }
        .content { padding: 20px; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        
        <div class="col-md-2 sidebar p-0">
            <div class="p-3 text-center border-bottom border-secondary">
                <h4 class="fw-bold"><i class="fa-solid fa-book-open"></i> SIMBS</h4>
                <small>Uji Kompetensi</small>
            </div>
            
            <div class="mt-3">
                <small class="text-uppercase text-muted px-3">Data Master</small>
                <a href="kategori.php" class="active"><i class="fa-solid fa-list me-2"></i> Data Kategori</a>
                <a href="buku.php"><i class="fa-solid fa-book me-2"></i> Data Buku</a>
                
                <small class="text-uppercase text-muted px-3 mt-3 d-block">Autentikasi</small>
                <a href="logout.php" class="text-danger"><i class="fa-solid fa-right-from-bracket me-2"></i> Sign Out</a>
            </div>
        </div>

        <div class="col-md-10 content">
            
            <nav class="navbar navbar-light bg-white shadow-sm mb-4 rounded px-3">
                <span class="navbar-brand mb-0 h1">Tambah Kategori</span>
                <div class="d-flex align-items-center">
                    <span class="me-3 text-secondary">Halo, <b><?= $_SESSION['username']; ?></b></span>
                    <img src="https://ui-avatars.com/api/?name=<?= $_SESSION['username']; ?>&background=random" class="rounded-circle" width="35">
                </div>
            </nav>

            <div class="card shadow-sm border-0" style="max-width: 600px;">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Form Tambah Kategori</h5>
                </div>
                <div class="card-body">
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" name="nama_kategori" class="form-control" placeholder="Contoh: Novel, Komik..." required>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" name="simpan" class="btn btn-primary">
                                <i class="fa-solid fa-save"></i> Simpan
                            </button>
                            <a href="kategori.php" class="btn btn-secondary">
                                <i class="fa-solid fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>