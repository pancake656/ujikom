<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php"); exit;
}

// --- LOGIKA DATA ---
$batas   = 5;
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;
$previous = $halaman - 1;
$next     = $halaman + 1;

$keyword = "";
if (isset($_GET['cari'])) {
    $keyword = $_GET['cari'];
    $where = "WHERE buku.judul LIKE '%$keyword%' OR kategori.nama_kategori LIKE '%$keyword%' OR buku.penulis LIKE '%$keyword%'";
} else {
    $where = "";
}

$sql_total = mysqli_query($koneksi, "SELECT buku.* FROM buku LEFT JOIN kategori ON buku.id_kategori = kategori.id_kategori $where");
$jumlah_data = mysqli_num_rows($sql_total);
$total_halaman = ceil($jumlah_data / $batas);

// Query dengan Group Concat untuk kategori multiple
$sql = "SELECT buku.*, 
        (SELECT GROUP_CONCAT(nama_kategori SEPARATOR ', ') 
         FROM kategori 
         WHERE FIND_IN_SET(kategori.id_kategori, buku.id_kategori)) as nama_kategori_lengkap
        FROM buku 
        $where 
        ORDER BY buku.tanggal_input DESC 
        LIMIT $halaman_awal, $batas";

$query_buku = mysqli_query($koneksi, $sql);
$nomor = $halaman_awal + 1;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Buku - SIMBS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #343a40; color: white; }
        .sidebar a { color: #cfd8dc; text-decoration: none; padding: 10px 15px; display: block; }
        .sidebar a:hover, .sidebar a.active { background-color: #495057; color: white; border-left: 4px solid #0d6efd; }
        .content { padding: 20px; }
        .cover-img { width: 50px; height: 70px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        
        <div class="col-md-2 sidebar p-0">
            <div class="p-3 text-center border-bottom border-secondary">
                <h4 class="fw-bold"><i class="fa-solid fa-book-open"></i> SIMBS</h4>
                <small>Sistem Informasi Manajemen Buku Sederhana</small>
            </div>
            
            <div class="mt-3">
                <a href="kategori.php"><i class="fa-solid fa-list me-2"></i> Data Kategori</a>
                <a href="buku.php" class="active"><i class="fa-solid fa-book me-2"></i> Data Buku</a>
                <a href="logout.php" class="text-danger mt-3"><i class="fa-solid fa-right-from-bracket me-2"></i> Sign Out</a>
            </div>
        </div>

        <div class="col-md-10 content">
            <nav class="navbar navbar-light bg-white shadow-sm mb-4 rounded px-3">
                <span class="navbar-brand mb-0 h1">Data Buku</span>
                <div class="d-flex align-items-center">
                    <span class="me-3 text-secondary">Halo, <b><?= $_SESSION['username']; ?></b></span>
                    <img src="https://ui-avatars.com/api/?name=<?= $_SESSION['username']; ?>&background=random" class="rounded-circle" width="35">
                </div>
            </nav>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-4">Daftar Buku Tersedia</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <a href="tambah_buku.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah Data</a>
                        </div>
                        <div class="col-md-6">
                            <form action="" method="GET" class="d-flex justify-content-end">
                                <div class="input-group" style="max-width: 300px;">
                                    <input type="text" name="cari" class="form-control" placeholder="Cari buku..." value="<?= $keyword; ?>">
                                    <button class="btn btn-primary" type="submit"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>ID</th>
                                    <th>Gambar</th>
                                    <th>Judul Buku</th>
                                    <th>Penulis / Penerbit</th>
                                    <th>Tahun</th>
                                    <th>Stok</th>
                                    <th>Kategori</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(mysqli_num_rows($query_buku) > 0): ?>
                                    <?php while($row = mysqli_fetch_array($query_buku)): ?>
                                    <tr>
                                        <td><?= $nomor++; ?></td>
                                        <td><span class="badge bg-secondary"><?= $row['id_buku']; ?></span></td>
                                        
                                        <td>
                                            <?php if($row['gambar'] != ""): ?>
                                                <img src="img/<?= $row['gambar']; ?>" class="cover-img">
                                            <?php else: ?>
                                                <span class="text-muted small">No Img</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="fw-bold"><?= $row['judul']; ?></td>
                                        <td>
                                            <small class="d-block text-muted">Penulis: <?= $row['penulis']; ?></small>
                                            <small class="d-block text-muted">Penerbit: <?= $row['penerbit']; ?></small>
                                        </td>
                                        <td><?= $row['tahun_terbit']; ?></td>
                                        <td><?= $row['stok']; ?></td>
                                        <td><span class="badge bg-info text-dark"><?= $row['nama_kategori_lengkap']; ?></span></td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="edit_buku.php?id=<?= $row['id_buku']; ?>" class="btn btn-success btn-sm">Edit</a>
                                                <a href="hapus_buku.php?id=<?= $row['id_buku']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus?')">Hapus</a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="9" class="text-center text-muted">Data tidak ditemukan.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <nav>
                        <ul class="pagination justify-content-end">
                            <li class="page-item <?= ($halaman <= 1) ? 'disabled' : ''; ?>"><a class="page-link" href="?halaman=<?= $previous ?>">Previous</a></li>
                            <li class="page-item disabled"><a class="page-link" href="#"><?= $halaman ?></a></li>
                            <li class="page-item <?= ($halaman >= $total_halaman) ? 'disabled' : ''; ?>"><a class="page-link" href="?halaman=<?= $next ?>">Next</a></li>
                        </ul>
                    </nav>

                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

