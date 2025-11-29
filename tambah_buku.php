<?php
session_start();
include 'koneksi.php';

// Cek Login
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
    header("Location: login.php"); exit;
}

if (isset($_POST['simpan'])) {
    $id_buku      = $_POST['id_buku'];
    $judul        = $_POST['judul'];
    $penulis      = $_POST['penulis'];
    $penerbit     = $_POST['penerbit'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $stok         = $_POST['stok'];
    
    // Gabung Kategori (Multi Select)
    $id_kategori  = isset($_POST['id_kategori']) ? implode(',', $_POST['id_kategori']) : '';

    // Cek ID Unik
    $cek = mysqli_query($koneksi, "SELECT id_buku FROM buku WHERE id_buku = '$id_buku'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Gagal! ID Buku sudah dipakai.'); window.history.back();</script>"; exit;
    }

    // --- LOGIKA UPLOAD GAMBAR (FIX FOLDER IMG) ---
    $rand = rand();
    $filename = $_FILES['gambar']['name'];
    $nama_gambar_db = "";

    if($filename != "") {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if(in_array($ext, ['png','jpg','jpeg'])) {
            // PERBAIKAN: Upload ke folder 'img/'
            move_uploaded_file($_FILES['gambar']['tmp_name'], 'img/'.$rand.'_'.$filename);
            $nama_gambar_db = $rand.'_'.$filename;
        } else {
            echo "<script>alert('Format gambar salah!'); window.history.back();</script>"; exit;
        }
    }

    // Simpan Data
    $query = "INSERT INTO buku (id_buku, judul, id_kategori, penulis, penerbit, tahun_terbit, stok, gambar) 
              VALUES ('$id_buku', '$judul', '$id_kategori', '$penulis', '$penerbit', '$tahun_terbit', '$stok', '$nama_gambar_db')";
    
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Berhasil menyimpan data!'); window.location='buku.php';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan data!'); window.history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Data Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style> body { background-color: #f8f9fa; } .card { border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); } </style>
</head>
<body>
<div class="container mt-5 mb-5">
    <div class="card" style="max-width: 700px; margin: auto;">
        <div class="card-header bg-white border-bottom"><h5 class="mb-0 fw-bold">Tambah Data Buku</h5></div>
        <div class="card-body p-4">
            <form method="POST" enctype="multipart/form-data">
                
                <div class="mb-3">
                    <label class="form-label fw-bold">ID Buku</label>
                    <input type="number" name="id_buku" class="form-control" placeholder="Contoh: 101" required>
                    <small class="text-danger">* ID Harus Unik</small>
                </div>

                <div class="mb-3"><label class="form-label">Judul Buku</label><input type="text" name="judul" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Penulis</label><input type="text" name="penulis" class="form-control" required></div>
                <div class="mb-3"><label class="form-label">Penerbit</label><input type="text" name="penerbit" class="form-control" required></div>

                <div class="row">
                    <div class="col-md-6 mb-3"><label class="form-label">Tahun Terbit</label><input type="number" name="tahun_terbit" class="form-control" required></div>
                    <div class="col-md-6 mb-3"><label class="form-label">Stok</label><input type="number" name="stok" class="form-control" required></div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kategori (Tahan CTRL untuk pilih banyak)</label>
                    <select name="id_kategori[]" class="form-select" multiple size="4" required>
                        <?php
                        $sql_kat = mysqli_query($koneksi, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
                        while($k = mysqli_fetch_array($sql_kat)): ?>
                            <option value="<?= $k['id_kategori']; ?>"><?= $k['nama_kategori']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label">Cover Gambar</label>
                    <input type="file" name="gambar" class="form-control">
                </div>

                <button type="submit" name="simpan" class="btn btn-primary px-4">Simpan</button>
                <a href="buku.php" class="btn btn-secondary px-4">Kembali</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>