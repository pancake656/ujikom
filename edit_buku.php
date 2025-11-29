<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php"); exit;
}

$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM buku WHERE id_buku = '$id'");
$data  = mysqli_fetch_array($query);

// Pecah string "1,2,5" menjadi array ["1", "2", "5"] agar bisa dicek nanti
$kategori_terpilih = explode(',', $data['id_kategori']);

if (isset($_POST['update'])) {
    $judul        = $_POST['judul'];
    $penulis      = $_POST['penulis'];
    $penerbit     = $_POST['penerbit'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $stok         = $_POST['stok'];
    $foto_lama    = $_POST['foto_lama'];
    
    // Gabungkan array menjadi string lagi saat update
    $id_kategori  = isset($_POST['id_kategori']) ? implode(',', $_POST['id_kategori']) : '';

    if ($_FILES['gambar']['error'] === 4) {
        $gambar = $foto_lama;
    } else {
        $rand = rand();
        $filename = $_FILES['gambar']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!in_array($ext, ['png','jpg','jpeg'])) {
            echo "<script>alert('Format salah!'); window.history.back();</script>"; exit;
        }
        $gambar = $rand.'_'.$filename;
        // PERBAIKAN DI SINI: Ubah 'gambar/' jadi 'img/'
        move_uploaded_file($_FILES['gambar']['tmp_name'], 'img/'.$gambar);
    }

    $update = mysqli_query($koneksi, "UPDATE buku SET 
                            judul       = '$judul',
                            penulis     = '$penulis',
                            penerbit    = '$penerbit',
                            tahun_terbit= '$tahun_terbit',
                            stok        = '$stok',
                            id_kategori = '$id_kategori',
                            gambar      = '$gambar'
                            WHERE id_buku = '$id'");
    
    if ($update) {
        echo "<script>alert('Update Berhasil!'); window.location='buku.php';</script>";
    } else {
        echo "<script>alert('Gagal!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card { border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    </style>
</head>
<body class="bg-light">
<div class="container mt-5 mb-5">
    <div class="card shadow-sm" style="max-width: 600px; margin: auto;">
        <div class="card-header bg-white"><h5 class="mb-0 fw-bold">Edit Data Buku</h5></div>
        <div class="card-body p-4">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="foto_lama" value="<?= $data['gambar']; ?>">

                <div class="mb-3">
                    <label class="form-label">Judul Buku</label>
                    <input type="text" name="judul" class="form-control" value="<?= $data['judul']; ?>" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Penulis</label>
                        <input type="text" name="penulis" class="form-control" value="<?= $data['penulis']; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Penerbit</label>
                        <input type="text" name="penerbit" class="form-control" value="<?= $data['penerbit']; ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tahun Terbit</label>
                        <input type="number" name="tahun_terbit" class="form-control" value="<?= $data['tahun_terbit']; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stok" class="form-control" value="<?= $data['stok']; ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kategori (Tahan CTRL untuk pilih banyak)</label>
                    <select name="id_kategori[]" class="form-select" multiple size="4" required>
                        <?php
                        $sql_kat = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
                        while($k = mysqli_fetch_array($sql_kat)): 
                            // Cek apakah ID kategori ini ada di dalam array buku yang sedang diedit?
                            $selected = in_array($k['id_kategori'], $kategori_terpilih) ? "selected" : "";
                        ?>
                            <option value="<?= $k['id_kategori']; ?>" <?= $selected; ?>><?= $k['nama_kategori']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="d-block form-label">Cover Saat Ini</label>
                    <?php if($data['gambar'] != ""): ?>
                        <img src="img/<?= $data['gambar']; ?>" width="80" class="mb-2 rounded border">
                    <?php endif; ?>
                    <input type="file" name="gambar" class="form-control mt-1">
                    <small class="text-muted">Biarkan kosong jika tidak ingin mengganti gambar.</small>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" name="update" class="btn btn-primary px-4">Ubah Data</button>
                    <a href="buku.php" class="btn btn-secondary px-4">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>