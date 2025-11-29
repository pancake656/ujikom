<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php");
    exit;
}

$batas   = 5; 
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

$previous = $halaman - 1;
$next     = $halaman + 1;

$keyword = "";
if (isset($_GET['cari'])) {
    $keyword = $_GET['cari'];
    $where = "WHERE nama_kategori LIKE '%$keyword%'";
} else {
    $where = "";
}

$sql_total = mysqli_query($koneksi, "SELECT * FROM kategori $where");
$jumlah_data = mysqli_num_rows($sql_total);
$total_halaman = ceil($jumlah_data / $batas);

$sql = "SELECT * FROM kategori $where ORDER BY id_kategori DESC LIMIT $halaman_awal, $batas";
$query_kategori = mysqli_query($koneksi, $sql);
$nomor = $halaman_awal + 1;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kategori - SIMBS</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body { background-color: #f8f9fa; }
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
            color: white;
        }
        .sidebar a {
            color: #cfd8dc;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #495057;
            color: white;
            border-left: 4px solid #0d6efd;
        }
        .content { padding: 20px; }
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
                <a href="kategori.php" class="active"><i class="fa-solid fa-list me-2"></i> Data Kategori</a>
                <a href="buku.php"><i class="fa-solid fa-book me-2"></i> Data Buku</a>
                
                <a href="logout.php" class="text-danger"><i class="fa-solid fa-right-from-bracket me-2"></i> Sign Out</a>
            </div>
        </div>

        <div class="col-md-10 content">
            
            <nav class="navbar navbar-light bg-white shadow-sm mb-4 rounded px-3">
                <span class="navbar-brand mb-0 h1">Data Kategori</span>
                <div class="d-flex align-items-center">
                    <span class="me-3 text-secondary">Halo, <b><?= $_SESSION['username']; ?></b></span>
                    <img src="https://ui-avatars.com/api/?name=<?= $_SESSION['username']; ?>&background=random" class="rounded-circle" width="35">
                </div>
            </nav>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title mb-4">Daftar Kategori Buku</h5>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <a href="tambah_kategori.php" class="btn btn-primary">
                                <i class="fa-solid fa-plus"></i> Tambah Kategori
                            </a>
                        </div>
                        <div class="col-md-6">
                            <form action="" method="GET" class="d-flex justify-content-end">
                                <div class="input-group" style="max-width: 300px;">
                                    <input type="text" name="cari" class="form-control" placeholder="Cari kategori..." value="<?= $keyword; ?>">
                                    <button class="btn btn-primary" type="submit"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th width="10%">No</th>
                                    <th>Nama Kategori</th>
                                    <th width="20%">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(mysqli_num_rows($query_kategori) > 0): ?>
                                    <?php while($row = mysqli_fetch_array($query_kategori)): ?>
                                    <tr>
                                        <td><?= $nomor++; ?></td>
                                        <td class="fw-bold"><?= $row['nama_kategori']; ?></td>
                                        <td>
                                            <a href="edit_kategori.php?id=<?= $row['id_kategori']; ?>" class="btn btn-success btn-sm">
                                                <i class="fa-solid fa-pen-to-square"></i> Edit
                                            </a>
                                            <a href="hapus_kategori.php?id=<?= $row['id_kategori']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus kategori ini?')">
                                                <i class="fa-solid fa-trash"></i> Hapus
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="3" class="text-center text-muted">Data tidak ditemukan.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <nav>
                        <ul class="pagination justify-content-end">
                            <li class="page-item <?= ($halaman <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?halaman=<?= $previous ?>&cari=<?= $keyword ?>">Previous</a>
                            </li>
                            <?php for($x = 1; $x <= $total_halaman; $x++): ?>
                                <li class="page-item <?= ($halaman == $x) ? 'active' : ''; ?>">
                                    <a class="page-link" href="?halaman=<?= $x ?>&cari=<?= $keyword ?>"><?= $x ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= ($halaman >= $total_halaman) ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?halaman=<?= $next ?>&cari=<?= $keyword ?>">Next</a>
                            </li>
                        </ul>
                    </nav>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>